<?php

namespace App\Services;

use App\Models\AliexpressToken;
use App\Services\AliExpressSDK\iop\IopClient;
use App\Services\AliExpressSDK\iop\IopRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AliExpressService
{
    protected $appKey;
    protected $appSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->appKey    = env('ALIEXPRESS_APP_KEY');
        $this->appSecret = env('ALIEXPRESS_APP_SECRET');
        $this->baseUrl   = rtrim(env('ALIEXPRESS_API_URL', 'https://api-sg.aliexpress.com/rest'), '/');
    }

    /**
     * Exchange authorization code for access token
     * @throws \Exception
     */
    public function generateSecurityToken(string $authCode): array
    {
        try {
            $c = new IopClient($this->baseUrl, $this->appKey, $this->appSecret);
            $request = new IopRequest('/auth/token/security/create');
            $request->addApiParam('code',$authCode);

            $response = $c->execute($request);

            $response = json_decode($response, true);

            AliexpressToken::updateOrCreate(
                ['id' => 1],
                [
                    'access_token'  => $response['access_token'] ?? null,
                    'refresh_token' => $response['refresh_token'] ?? null,
                    'expires_at'    => isset($response['expires_in']) ? now()->addSeconds($response['expires_in']) : null,
                ]
            );

            return ['status' => 'success'];
        } catch (\Exception $exception) {
            Log::error('AliExpress Token Error: ' . $exception->getMessage(), [
                'trace' => $exception->getTraceAsString(),
            ]);
            return ['status' => 'error', 'message' => $exception->getMessage()];
        }
    }

    /**
     * Refresh token
     */
    public function refreshToken(string $refreshToken): array
    {
        $payload = [
            'grant_type'    => 'refresh_token',
            'client_id'     => $this->appKey,
            'client_secret' => $this->appSecret,
            'refresh_token' => $refreshToken,
        ];

        $res = Http::asForm()->post("{$this->baseUrl}/auth/token/refresh", $payload);

        if ($res->failed()) {
            Log::debug('AE refreshToken error', ['body' => $res->body(), 'status' => $res->status()]);
            return ['error' => $res->json() ?: $res->body()];
        }

        return $res->json();
    }

    /**
     * Security token (server-level, optional)
     */
    public function getSecurityToken(): array
    {
        $payload = [
            'client_id'     => $this->appKey,
            'client_secret' => $this->appSecret,
        ];

        $res = Http::asForm()->post("{$this->baseUrl}/auth/token/security/create", $payload);

        if ($res->failed()) {
            Log::debug('AE getSecurityToken error', ['body' => $res->body(), 'status' => $res->status()]);
            return ['error' => $res->json() ?: $res->body()];
        }

        return $res->json();
    }

    /**
     * List promotion products (simple wrapper).
     * The exact API parameters may vary—adjust fields as needed.
     */
    public function listProducts(string $accessToken, array $params = []): array
    {
        // minimal required params
        $payload = [
            'app_key'   => $this->appKey,
            'access_token' => $accessToken,
            'fields'    => $params['fields'] ?? 'productId,productTitle,salePrice,originalPrice,imageUrl',
            'page_size' => $params['page_size'] ?? 10,
            'page_no'   => $params['page_no'] ?? 1,
        ];

        if (!empty($params['keywords'])) {
            $payload['keywords'] = $params['keywords'];
        }

        $res = Http::asForm()->post("{$this->baseUrl}/api.listPromotionProduct/", $payload);

        if ($res->failed()) {
            Log::debug('AE listProducts error', ['body' => $res->body(), 'status' => $res->status()]);
            return ['error' => $res->json() ?: $res->body()];
        }

        return $res->json();
    }

    /**
     * Get categories
     */
    public function getCategories(string $accessToken): array
    {
        $payload = [
            'app_key'      => $this->appKey,
            'access_token' => $accessToken,
        ];

        $res = Http::asForm()->post("{$this->baseUrl}/api.listPromotionCategory/", $payload);

        if ($res->failed()) {
            Log::debug('AE getCategories error', ['body' => $res->body(), 'status' => $res->status()]);
            return ['error' => $res->json() ?: $res->body()];
        }

        return $res->json();
    }
}
