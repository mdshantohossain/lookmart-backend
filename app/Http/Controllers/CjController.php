<?php

namespace App\Http\Controllers;

use App\Http\Requests\CjTokenRequest;
use App\Models\CJToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CjController extends Controller
{
    public string $tokenKey = 'cj_token';
    public function index(Request $request)
    {
        $token = Cache::get($this->tokenKey);

        if(!$token){
            $token = $this->getCachedToken();
        }

        $query = $request->input('query');

        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->get("https://developers.cjdropshipping.com/api2.0/v1/product/query", [
            'productSku' => $query,
        ]);

        $data = $response->json('data');

        if($data) {
            return response()->json([
                'code' => 200,
                'data' => $data
            ]);
        }

        return response()->json([
            'code' => 404,
            'data' => [],
        ]);
    }

    public function create()
    {
        $token = Cache::get($this->tokenKey);

        if(!$token){
            $token = $this->getCachedToken();
        }

        return view('admin.cj-token.index', compact('token'));
    }
    public function store(CjTokenRequest $request)
    {
        $token = $request->validated();
        $storedToken = $this->getToken();

        if($storedToken) {
            $storedToken->update($token);
            $message = 'Token updated successfully!';
        } else {
           CJToken::create($token);
           $message = 'Token created successfully!';
        }

        // cache revalidate
        $this->revalidateToken();

        return redirect()->route('dashboard')->with('success', $message);

    }

    public function revalidateToken()
    {
        $token = $this->getToken()?->token;

        Cache::put($this->tokenKey, $token);
    }

    public function getToken(): ?CjToken
    {
       return CJToken::first();

    }

    private function getCachedToken(): ?string
    {
        $token = Cache::get($this->tokenKey);

        if (!$token) {
            $token = $this->getToken()?->token;

            if ($token) {
                Cache::set($this->tokenKey, $token);
            }
        }

        return $token;
    }
}
