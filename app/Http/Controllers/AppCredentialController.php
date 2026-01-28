<?php

namespace App\Http\Controllers;
use App\Models\AppCredential;
use App\Models\MailCredential;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppCredentialController extends Controller
{
    public function index(): View
    {
        return view('admin.app-credential.index', [
            'googleLoginCredential' => AppCredential::where('credential_for', 'google_login')->first(),
            'facebookLoginCredential' => AppCredential::where('credential_for', 'facebook_login')->first(),
            'mailCredential' => MailCredential::first(),
        ]);
    }

    public function changeGoogleLoginCredential(Request $request): RedirectResponse
    {
        $request->validate([
            'credential_for' => 'required',
            'google_client_id' => 'required',
            'google_client_secret' => 'required',
        ]);
        try {
            $googleCredential = AppCredential::where('credential_for', $request->credential_for)->first();

            if (!$googleCredential) {
                AppCredential::create([
                    'credential_for' => $request->credential_for,
                    'client_id' => $request->google_client_id,
                    'client_secret' => $request->google_client_secret,
                ]);

                return redirect('/dashboard')->with('success', 'Google login credential created successfully');
            } else {
                $googleCredential->update([
                    'credential_for' => $request->google_credential_for,
                    'client_id' => $request->google_client_id,
                    'client_secret' => $request->google_client_secret,
                ]);

                return redirect('/dashboard')->with('success', 'Google login credential changed successfully');
            }

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function changeFacebookLoginCredential(Request $request): RedirectResponse
    {
        $request->validate([
            'credential_for' => 'required',
            'facebook_client_id' => 'required',
            'facebook_client_secret' => 'required',
        ]);

        try {
            $facebookCredential = AppCredential::where('credential_for', $request->credential_for)->first();

            if (!$facebookCredential) {
                AppCredential::create([
                    'credential_for' => $request->credential_for,
                    'client_id' => $request->facebook_client_id,
                    'client_secret' => $request->facebook_client_secret,
                ]);

                return redirect('/dashboard')->with('success', 'Facebook login credential created successfully');
            } else {

                $facebookCredential->update([
                    'credential_for' => $request->credential_for,
                    'client_id' => $request->facebook_client_id,
                    'client_secret' => $request->facebook_client_secret,
                ]);

                return redirect('/dashboard')->with('success', 'Facebook login credential changed successfully');
            }

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }

    public function changeMailCredential(Request $request): RedirectResponse
    {
        try {
            $mailCredential = MailCredential::first();

            if (!$mailCredential) {
                MailCredential::create($request);
                return redirect('/dashboard')->with('success', 'Mail credential created successfully');
            } else {
                $mailCredential->update($request);
                return redirect('/dashboard')->with('success', 'Mail credential changed successfully');
            }

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
