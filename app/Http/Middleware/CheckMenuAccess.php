<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Super Admin memiliki akses ke semua menu
        if ($user->status === 'Super Admin') {
            return $next($request);
        }

        // HRIS dan Laporan dapat diakses semua user
        $currentRoute = $request->route()->getName();
        // if (str_contains($currentRoute, 'hris') || str_contains($currentRoute, 'laporan')) {
        //     return $next($request);
        // }

        // Cek akses menu berdasarkan data di tabel usermenu
        $menuName = $this->getMenuNameFromRoute($currentRoute);
        if ($menuName && $user->menus()->where('nama', $menuName)->exists()) {
            return $next($request);
        }

        // Jika tidak memiliki akses, redirect ke dashboard dengan pesan error
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke menu tersebut. Silahkan hubungi Super Admin.');
    }

    private function getMenuNameFromRoute($route)
    {
        // Mapping route ke nama menu
        $menuMap = [
            'warehouse' => 'Warehouse',
            'finance' => 'Finance',
            'hrd' => 'HRD',
            'security' => 'Security',
            'maintenance' => 'Maintenance',
            'suhu' => 'Suhu'
        ];

        foreach ($menuMap as $routeKey => $menuName) {
            if (str_contains($route, $routeKey)) {
                return $menuName;
            }
        }

        return null;
    }
} 