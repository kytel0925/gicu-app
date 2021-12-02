<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Class AppCacheClear
 * @package App\Console\Commands
 */
class AppCacheClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all the caches in this application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $output = '';
        Artisan::call('cache:clear');
        $output .= Artisan::output();
        Artisan::call('view:clear');
        $output .= Artisan::output();
        Artisan::call('config:clear');
        $output .= Artisan::output();
        Artisan::call('event:clear');
        $output .= Artisan::output();
        Artisan::call('route:clear');
        $output .= Artisan::output();

        $this->info($output);

        return Command::SUCCESS;
    }
}
