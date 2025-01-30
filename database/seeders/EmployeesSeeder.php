<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employees;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employees::create([
            'id' => 'MLIIOF0001',
            'nama' => 'Rana',
            'divisi' => 'Internal Office 1',
        ]);
    }
}
