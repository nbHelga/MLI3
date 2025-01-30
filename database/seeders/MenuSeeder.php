<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'nama' => 'Admin',
        ]);
        Menu::create([
            'nama' => 'HRIS',
        ]);
        Menu::create([
            'nama' => 'HRD',
        ]);
        Menu::create([
            'nama' => 'Finance',
        ]);
        Menu::create([
            'nama' => 'Warehouse',
        ]);
        Menu::create([
            'nama' => 'Maintenance Report',
        ]);
        Menu::create([
            'nama' => 'Back Office',
        ]);
    }
}
