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
            'nama' => 'HRD',
        ]);
        Menu::create([
            'nama' => 'Finance',
        ]);
        Menu::create([
            'nama' => 'Warehouse',
            'submenu' => 'CS01',
        ]);

        Menu::create([
            'nama' => 'Warehouse',
            'submenu' => 'CS02',
        ]);

        Menu::create([
            'nama' => 'Suhu',
        ]);

        Menu::create([
            'nama' => 'Maintenance',
        ]);

        Menu::create([
            'nama' => 'Security',
        ]);
    }
}
