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
        Department::create([
            ['kode_dept'=>'IOF','nama_dept'=>'INTERNAL OFFICE'],
            ['kode_dept'=>'BOF','nama_dept'=>'BACK OFFICE'],
            ['kode_dept'=>'FIN','nama_dept'=>'FINANCE'],
            ['kode_dept'=>'HRD','nama_dept'=>'HUMAN RESOURCE'],
            ['kode_dept'=>'FIN','nama_dept'=>'FINANCE'],
            ['kode_dept'=>'MNT','nama_dept'=>'MAINTENANCE'],
            ['kode_dept'=>'SEC','nama_dept'=>'SECURITY'],
            ['kode_dept'=>'WRH','nama_dept'=>'WAREHOUSE'],
        ]);
    }
}
