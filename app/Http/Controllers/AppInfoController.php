<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use Illuminate\Http\JsonResponse;
use App\Models\AppInfo;
use Illuminate\Support\Facades\Cache;

class AppInfoController extends Controller
{
    protected string $cachedKey = 'app.info';

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

        $appManager = $this->appInfo();

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

        return back()->with('success', $message);
    }

    public function appInfo()
    {
        return Cache::remember($this->cachedKey, now()->addDays(15), function () {
            return AppInfo::first();
        });
    }

    public function getAppInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->appInfo()
        ]);
    }
}
