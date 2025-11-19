<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppServe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Application starting on network serve...');
        passthru('php artisan serve --host=192.168.0.106');
    }
}
