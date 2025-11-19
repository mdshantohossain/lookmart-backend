<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AliExpressService;
use App\Models\AliExpressToken;

class AliExpressController extends Controller
{
    protected $svc;

    public function __construct(AliExpressService $aliExpressService)
    {
        $this->svc = $aliExpressService;
    }

    // Redirect user to AliExpress authorization page
    public function redirectToAliExpress()
    {
        logger([
            'app_key' => env('ALIEXPRESS_APP_KEY'),
            'app_secret' => env('ALIEXPRESS_APP_SECRET'),
        ]);
        $url = "https://api-sg.aliexpress.com/oauth/authorize?" . http_build_query([
                'response_type' => 'code',
                'client_id'     => env('ALIEXPRESS_APP_KEY'),
                'redirect_uri'  => env('ALIEXPRESS_CALLBACK_URL'),
                'state'         => csrf_token(),
            ]);

        return redirect()->away($url);
    }

    // Handle callback, exchange code for tokens, persist them
    public function handleCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['error' => 'No code received from AliExpress'], 400);
        }

        $tokenResp = $this->svc->generateSecurityToken($code);

        if ($tokenResp['status'] === 'success') {
            return redirect()->route('/')
                ->with('success', 'AliExpress token stored successfully');
        }


        return redirect()->route('/')
            ->with('error', 'AliExpress error: ' . ($tokenResp['message'] ?? 'Unknown'));
    }

    // Manual refresh endpoint (for testing)
    public function refreshToken()
    {
        $row = AliExpressToken::first();
        if (!$row || !$row->refresh_token) {
            return response()->json(['error' => 'No refresh token available'], 400);
        }

        $resp = $this->svc->refreshToken($row->refresh_token);

        if (isset($resp['error'])) {
            return response()->json(['error' => $resp], 400);
        }

        // update DB
        $row->update([
            'access_token'  => $resp['access_token'] ?? $row->access_token,
            'refresh_token' => $resp['refresh_token'] ?? $row->refresh_token,
            'expires_at'    => isset($resp['expires_in']) ? now()->addSeconds($resp['expires_in']) : $row->expires_at,
        ]);

        return redirect()->route('dashboard')->with('success', 'Token refresh successful');
    }

    // Get security token (server-to-server) and return it (do not persist unless needed)
    public function getSecurityToken()
    {
        $resp = $this->svc->getSecurityToken();

        if (isset($resp['error'])) {
            return response()->json(['error' => $resp], 400);
        }

        return response()->json($resp);
    }

    // Fetch products (uses DB token)
    public function getProducts(Request $request)
    {
        $row = AliExpressToken::first();
        if (!$row || !$row->access_token) {
            return response()->json(['error' => 'No access token available. Authorize first.'], 400);
        }

        $params = [
            'page_size' => $request->query('page_size', 10),
            'page_no'   => $request->query('page_no', 1),
            'keywords'  => $request->query('q'),
            'timestamp'
        ];

        $resp = $this->svc->listProducts($row->access_token, $params);

        logger($resp);

        return response()->json($resp);
    }

    // Fetch categories (uses DB token)
    public function getCategories(Request $request)
    {
        $row = AliExpressToken::first();
        if (!$row || !$row->access_token) {
            return response()->json(['error' => 'No access token available. Authorize first.'], 400);
        }

        $resp = $this->svc->getCategories($row->access_token);
        return response()->json($resp);
    }
}
