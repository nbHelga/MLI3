<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckSubmenuAccess
{
    public function handle($request, Closure $next, $submenu)
    {
        $user = auth()->user();
        
        // Gunakan relasi menus yang sudah ada dan cek submenu
        if (!$user->menus()->where('submenu', $submenu)->exists()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke menu tersebut. Silahkan hubungi Super Admin.');
            // abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
} 