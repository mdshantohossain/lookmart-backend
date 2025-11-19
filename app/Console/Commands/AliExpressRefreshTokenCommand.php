<?php

namespace App\Console\Commands;

use App\Models\AliexpressToken;
use App\Services\AliExpressService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AliExpressRefreshTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliexpress:refresh-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(AliExpressService $svc)
    {
        $row = AliexpressToken::first();
        if (!$row || !$row->refresh_token) {
            $this->error('No refresh token found.');
            return 1;
        }

        $resp = $svc->refreshToken($row->refresh_token);

        if (isset($resp['error'])) {
            Log::error('AliExpress token refresh failed', ['resp' => $resp]);
            $this->error('Refresh failed. Check logs.');
            return 1;
        }

        $row->update([
            'access_token'  => $resp['access_token'] ?? $row->access_token,
            'refresh_token' => $resp['refresh_token'] ?? $row->refresh_token,
            'expires_at'    => isset($resp['expires_in']) ? now()->addSeconds($resp['expires_in']) : $row->expires_at,
        ]);

        $this->info('AliExpress token refreshed.');
        return 0;
    }
}
