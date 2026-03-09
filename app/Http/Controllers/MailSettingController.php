<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailSettingRequest;
use App\Services\EnvEditor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\View;

class MailSettingController extends Controller
{
    public function index(): View
    {
        isAuthorized('mail-setting module');

        return view('admin.email-setting.index');
    }

    /**
     * @throws \Throwable
     */
    public function store(MailSettingRequest $request, EnvEditor $envEditor): RedirectResponse
    {
        isAuthorized('mail-setting store');

        $data = [
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ];

        $envEditor->updateEnvValues($data);

        return redirect()->route('mail.setting')
            ->with('success', 'Settings updated successfully!');
    }
}
