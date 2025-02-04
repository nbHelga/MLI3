<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TempatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tempat')->insert([
            [
                'nama' => 'CS01',
                'ruangan' => 'Room 1',
            ],
            [
                'nama' => 'CS01',
                'ruangan' => 'Room 2',
            ],
            [
                'nama' => 'CS01',
                'ruangan' => 'Room 3',
            ],
            [
                'nama' => 'CS02',
                'ruangan' => 'Room 1',
            ],
            [
                'nama' => 'CS02',
                'ruangan' => 'Room 2',
            ],
            [
                'nama' => 'CS02',
                'ruangan' => 'Room 3',
            ],
            [
                'nama' => 'CS02',
                'ruangan' => 'Room 4',
            ],
            [
                'nama' => 'MASAL',
                'ruangan' => 'Room 1',
            ],
            [
                'nama' => 'Kantor',
                'ruangan' => '',
            ],
        ]);
    }
}
