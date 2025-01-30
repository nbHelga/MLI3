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

        UserMenu::create([
            'id_employees' => 'MLIIOF0001',
            'id_menu' => 1,
        ]);
        // UserMenu::create([
        //     'id_employees' => 'MLIIOF0001',
        //     'id_menu' => 2,
        // ]);
        // UserMenu::create([
        //     'id_employees' => 'MLIIOF0001',
        //     'id_menu' => 3,
        // ]);
        // UserMenu::create([
        //     'id_employees' => 'MLIIOF0001',
        //     'id_menu' => 4,
        // ]);
        // UserMenu::create([
        //     'id_employees' => 'MLIIOF0001',
        //     'id_menu' => 5,
        // ]);
        // UserMenu::create([
        //     'id_employees' => 'MLIIOF0001',
        //     'id_menu' => 6,
        // ]);
    }
}

