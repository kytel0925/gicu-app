<?php

namespace App\Console\Commands;

use CTDesarrollo\Regulus\Helpers\MongoDBResults;
use CTDesarrollo\Regulus\Models\Report;
use CTDesarrollo\Regulus\Models\ReportRequest;
use Doctrine\Inflector\Inflector;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\Language;
use Transliterator;

class ImportWorksheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:worksheet {worksheet} {--D|disk=local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a worksheet into a report result';

    /**
     * @var Report
     */
    protected $report;

    /**
     * @var ReportRequest
     */
    protected $request;

    /**
     * @var Inflector
     */
    protected $inflector;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->inflector = InflectorFactory::createForLanguage(Language::SPANISH)->build();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->verifyFile();

        $this->loadReport();

        $this->loadRequest();

        $this->importDataFromSpreadsheet();

        return Command::SUCCESS;
    }

    protected function verifyFile()
    {
        $exists = Storage::disk($this->option('disk'))->exists($this->argument('worksheet'));

        if(!$exists){
            $this->error("worksheet[{$this->argument('worksheet')}] not found in disk[{$this->option('disk')}]");
            exit(Command::FAILURE);
        }

        return $this;
    }

    protected function loadReport()
    {
        $id = $this->ask('report (id|code):');

        /** @var Report $report */
        $report = Report::query()->where('id', $id)->orWhere('code', $id)->first();

        if(!$report){
            $this->info("report[$id] not found a new one will be created");

            $values = $this->ask('enter a code,name,data_fetcher');
            $values = collect(explode(',', $values))->map('trim')->filter();
            $report->fill([
                'code' => $values->shift(),
                'name' => $values->shift(),
                'data_fetcher' => $values->shift(),
            ]);

            $report->save();
        }

        $this->info($report->toJson(), 'v');

        $this->report = $report;

        return $this;
    }

    protected function loadRequest()
    {
        $id = $this->ask('request (id|code):');
        /** @var ReportRequest $request */
        $request = ReportRequest::query()
            ->where('id', $id)
            ->orWhere('code', $id)
            ->first();

        if($request){
            if($request->report_id != $this->report->id){
                $this->error("the request[$request->id:$request->report_id] is not related to the report[{$this->report->id}]");

                exit(Command::FAILURE);
            }
            $this->info('the data storage will be cleaned');

            $this->cleanDataStorage($request);
        }
        else{
            $this->info("request[$id] not found a new one will be created");

            $code = trim($this->ask('enter a code', $id));
            $title = trim($this->ask('enter a title', $id));

            $request = new ReportRequest();
            $request->fill([
                'user_id' => 'artisan',
                'report_id' => $this->report->id,
                'code' => $code,
                'title' => $title,
                'data_id' => $code . '_' . uniqid(),
                'filters' => [],
                'filters_hash' => md5(json_encode([])),
            ]);

            $request->save();
        }

        $this->info($request->toJson(), 'v');

        $this->request = $request;

        return $this;
    }

    protected function cleanDataStorage(ReportRequest $request)
    {
        $results = new MongoDBResults($request->data_id);

        $rows = $results->builder()->delete();

        $this->info("data storage $request->data_id cleaned $rows row(s) affected");

        return $this;
    }

    protected function importDataFromSpreadsheet()
    {
        $spreadsheet = IOFactory::load(storage_path('app/' . $this->argument('worksheet')));

        $names = $spreadsheet->getSheetNames();

        $sheetName = $this->request->code;

        if(!in_array($sheetName, $names)){
            if(count($names) === 1){
                $sheetName = array_shift($names);
            }
            else{
                $sheetName = $this->choice('please select the worksheet for this import', $names);
            }
        }

        $this->info("the request[{$this->request->id}:{$this->request->code}] will be filled with the sheet[$sheetName]");

        $sheet = $spreadsheet->getSheetByName($sheetName);

        $headers = $this->getRowValues($sheet, 1)->map(function ($title){
            $key = strtolower(str_replace(' ', '_', $title));
            //goodbye accents
            $rule = 'NFD; [:Nonspacing Mark:] Remove; NFC';
            $what = Transliterator::create($rule);

            $key = $what->transliterate($key);

            return [
                'key' => $key,
                'title' => $title,
            ];
        });

        if($headers->isEmpty())
            abort(500, "empty header row in sheet[$sheetName]");

        $this->info('the data will be stored using the first column as the key maps[key => value of each row], please review the results of that');
        $this->info($headers->toJson(JSON_PRETTY_PRINT));

        if($this->choice('Are the values correct?', ['Y', 'N']) == 'N'){
            $headers = $headers->map(function ($header){
                $column = data_get($header, 'key');

                $column = $this->ask("Enter the new key for the column (name with a variable syntax):", $column);

                data_set($header, 'key', $column);

                return $header;
            });
        }

        $results = new MongoDBResults($this->request->data_id);

        $total = $this->getMaxRow($sheet);
        $ids = collect();
        $row = 1;

        $progress = $this->output->createProgressBar($total);
        $this->info("total of rows to be imported $total");
        $this->request->started_at = now();
        $this->request->save();

        $progress->start();
        while (
            ($values = $this->getRowValues($sheet, ++$row)) &&
            $values->isNotEmpty()
        ){
            $payload = $headers->mapWithKeys(function ($header, $index) use($values){
                $value = $values->get($index);
                $key = data_get($header, 'key');

                return [$key => $value];
            });

            $ids->push($results->builder()->insertGetId($payload->toArray()));

            $progress->advance();
        }

        $progress->finish();
        $this->request->finished_at = now();
        $this->request->save();

        $this->newLine();
        $this->info("{$ids->count()} records generated");
        $this->info($ids->toJson(), 'v');
    }

    /**
     * @param Worksheet $sheet
     * @param int $rowIndex
     * @param string $emptyBreakValue
     * @return Collection
     */
    protected function getRowValues(Worksheet $sheet, $rowIndex, $emptyBreakValue = '')
    {
        $row = $sheet->getRowIterator($rowIndex, 1)->current();
        $cellIterator = $row->getCellIterator();
        $data = collect();

        while (
            ($cell = $cellIterator->current()) &&
            ($value = trim($cell->getValue())) &&
            $value !== $emptyBreakValue
        ){
            $data->put($cell->getColumn(), $value);

            $cellIterator->next();
        }

        return $data;
    }

    /**
     * @param Worksheet $sheet
     * @return int
     */
    protected function getMaxRow(Worksheet $sheet)
    {
        $total = 2;

        do{
            $values = $this->getRowValues($sheet, $total++);
        }
        while ($values->isNotEmpty());

        return --$total;
    }
}
