<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            ['kode_dept' => 'IOF', 'nama_dept' => 'INTERNAL OFFICE', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'BOF', 'nama_dept' => 'BACK OFFICE', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'FIN', 'nama_dept' => 'FINANCE', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'HRD', 'nama_dept' => 'HUMAN RESOURCE', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'MNT', 'nama_dept' => 'MAINTENANCE', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'SEC', 'nama_dept' => 'SECURITY', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'WRH', 'nama_dept' => 'WAREHOUSE', 'created_at' => now(), 'updated_at' => now()],
            ['kode_dept' => 'MKT', 'nama_dept' => 'MARKETING', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
