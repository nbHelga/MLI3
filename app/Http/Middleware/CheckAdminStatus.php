<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminStatus
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if (!in_array($user->status, ['Administrator', 'Super Admin'])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke menu tersebut. Silahkan hubungi Super Admin.');
            // abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
} 