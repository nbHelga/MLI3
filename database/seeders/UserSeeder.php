<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 'MLIIOF0001', // Menggunakan id dari tabel employees
            'username' => 'admin',
            'password' => bcrypt('admin'), // Menggunakan bcrypt untuk hashing password
            'status' => 'Super Admin',
        ]);

        User::create([
            'id' => 'MLIFIN0002', // Menggunakan id dari tabel employees
            'username' => 'eka',
            'password' => bcrypt('eka'), // Menggunakan bcrypt untuk hashing password
        ]);  

        User::create([
            'id' => 'MLIMKT0003', // Menggunakan id dari tabel employees
            'username' => 'kevin',
            'password' => bcrypt('kevin'), // Menggunakan bcrypt untuk hashing password
        ]); 
    }
}
