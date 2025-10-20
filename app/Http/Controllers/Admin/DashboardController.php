<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(MailService $mailService): View
    {
        $mailService->welcomeMail([
            'name' => 'Md Shanto',
            'email' => 'shantohossain259@gmail.com'
        ]);

        return view('admin.dashboard.index');
    }
}
