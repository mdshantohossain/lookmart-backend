<?php

namespace App\Console\Commands;

use App\Jobs\SyncAliExpressProducts;
use Illuminate\Console\Command;

class SyncAliExpressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliexpress:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync AliExpress products periodically';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dispatch(new SyncAliExpressProducts());
        $this->info('AliExpress products sync started.');
    }
}
