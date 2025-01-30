<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('maintenance.dashboard');
    }
} 