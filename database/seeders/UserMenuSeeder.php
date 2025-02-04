<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserMenu;
use App\Models\Employees;
use App\Models\Menu;

class UserMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Berikan akses ke semua menu untuk Super Admin
        $menuIds = Menu::pluck('id');
        foreach ($menuIds as $menuId) {
            UserMenu::create([
                'id_users' => 'MLIIOF0001',
                'id_menu' => $menuId,
            ]);
        }

        // Untuk user lain, secara default tidak diberikan akses khusus
        // Akses akan diberikan melalui halaman admin
    }
}

