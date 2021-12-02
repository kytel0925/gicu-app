<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\ViewProps;
use CTDesarrollo\Regulus\Models\Report;
use CTDesarrollo\Regulus\Models\ReportRequest;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ReportResults
 * @package App\Http\Controllers
 */
class ReportResults extends Controller
{
    use ViewProps;

    /**
     * @return Response
     */
    public function index($report, $request)
    {
        $report = $this->loadReport($report); //@todo move this code to a loader class <I>{public function load($arguments = []? constructor) : mixed}

        $request = $this->loadRequest($request);

        //@validation pipes ides $pipes = [LoadReport, LoadRequest] then(Validate)
        if($request->report_id != $report->id)
            abort('400', "the request[$request->id] is invalid for the report[$report->id]");

        $data = static::getProps()->merge([
            'report' => $report,
            'request' => $request,
        ])->toArray();

        return Inertia::render('ReportResults', $data);
    }

    /**
     * @param mixed $report
     * @return Report
     */
    protected function loadReport($report) : Report
    {
        if($report instanceof Report){
            return $report;
        }

        /** @var Report $report */
        if(is_numeric($report)){
            $report = Report::query()->findOrFail($report);
        }
        elseif(is_string($report)){
            $report = Report::query()
                ->where('code', $report)
                ->firstOrFail();
        }
        else{
            abort('400', 'invalid type in report load');
        }

        return $report;
    }

    /**
     * @param mixed $request
     * @return ReportRequest
     */
    public function loadRequest($request)
    {
        if($request instanceof ReportRequest){
            return $request;
        }

        /** @var ReportRequest $request */
        if(is_numeric($request)){
            $request = ReportRequest::query()->findOrFail($request);
        }
        elseif (is_string($request)){
            $request = ReportRequest::query()
                ->where('code', $request)
                ->firstOrFail();
        }
        else{
            abort('400', 'invalid type in report-request load');
        }

        return $request;
    }
}
