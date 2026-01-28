<?php

namespace App\Http\Controllers;
use App\Http\Requests\AppManageRequest;
use App\Models\AppManage;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppManageController extends Controller
{
    public function index()
    {
        return view('admin.app-credential.app-manage', [
            'app' => AppManage::first()
        ]);
    }
    public function store(Request $request)
    {
        $appManager = AppManage::first();

        $inputs = $request->except('_token');

        foreach (['logo', 'favicon'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                if($appManager && file_exists($appManager->$fileKey)) {
                    removeImage($appManager->$fileKey);
                }

                $inputs[$fileKey] = getImageUrl($request->file($fileKey), 'images/app-manage');
            }
        }

        if (!$appManager) {
            AppManage::create($inputs);
            $message = 'App Manager Added Successfully';

        } else {
            $appManager->update($inputs);
            $message = 'App Manager Updated Successfully';
        }
        return back()->with('success', $message);
    }

    public function getAppInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => AppManage::first()
        ]);
    }
}
