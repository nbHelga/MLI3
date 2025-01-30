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
            'name' => 'RVN',
            'username'=>'rvn',
            'email'=>'rvn@gmail.com',
            'email_verified_at'=>now(),
            'password'=>Hash::make('password'),
            'remember_token'=> Str::random(10)  
        ]);

        User::factory(5)->create();
        User::create([
            'id' => 'MLIIOF0001', // Menggunakan id dari tabel employees
            'username' => 'admin',
            'password' => bcrypt('admin'), // Menggunakan bcrypt untuk hashing password
        ]);
    }
}
