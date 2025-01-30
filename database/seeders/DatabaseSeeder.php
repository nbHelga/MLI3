<?php

namespace Database\Seeders;
use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        // Category::create([
        //     'name' => 'Web Design',
        //     'slug'=>'web-design',
        // ]);
        // Post::create([
        //     'title' => 'Judul 1',
        //     'author_id'=> 1,
        //     'category_id'=> 1,
        //     'slug'=> 'judul-1',
        //     'body'=>'A seeder class only contains one method by default: run. This method is called when the db:seed Artisan command is executed. Within the run method, you may insert data into your database however you wish. You may use the query builder to manually insert data or you may use Eloquent model factories.', 
        // ]);
        $this->call([CategorySeeder::class, UserSeeder::class]);
        Post::factory(100)->recycle([
            Category::all(),
            User::all(), 

            
        ])->create();

        $this->call([
            EmployeesSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            UserMenuSeeder::class,
            TempatSeeder::class,
        ]);
    }
}
