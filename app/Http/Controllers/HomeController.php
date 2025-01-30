<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        Log::info('User accessed homeadmin', ['user_id' => Auth::id()]);
        return view('auth.home');
    }
}
