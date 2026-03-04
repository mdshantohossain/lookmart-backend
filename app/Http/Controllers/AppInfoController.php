<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use Illuminate\Http\JsonResponse;
use App\Models\AppInfo;
use Illuminate\Support\Facades\Redis;

class AppInfoController extends Controller
{
    public function index()
    {
        isAuthorized('app-info module');

        return view('admin.app-info.index', [
            'app' => $this->appInfo()
        ]);
    }
    public function store(AppInfoRequest $request)
    {
        isAuthorized('app-info store');

        $appManager = AppInfo::first();

        $inputs = $request->except('_token');

        foreach (['logo', 'favicon'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                if($appManager && file_exists($appManager->$fileKey)) {
                    removeImage($appManager->$fileKey);
                }

                $inputs[$fileKey] = getImageUrl($request->file($fileKey), 'images/app-info');
            }
        }

        if (!$appManager) {
            AppInfo::create($inputs);
            $message = 'App info added Successfully';

        } else {
            $appManager->update($inputs);
            $message = 'App info updated Successfully';
        }

        // rebuild cache after create or update
        $this->cacheRebuild();

        return back()->with('success', $message);
    }

    public function cacheRebuild(): void
    {
        $app = AppInfo::first();

        Redis::set('app', json_encode($app));
    }

    public function getAppInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->appInfo()
        ]);
    }

    public function appInfo()
    {
        $cached = Redis::get('app');

        if($cached) {
            $app = json_decode($cached);
        } else {
            $app = AppInfo::first();

            Redis::set('app', json_encode($app));
        }

        return $app;
    }
}
