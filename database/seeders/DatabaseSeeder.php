<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            // TestSeeder::class,
            LessonSeeder::class,
            QuizSeeder::class,
        ]);

        $userAdmin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);

        $userAdmin->player()->create([
            'username' => 'admin_user',
        ]);

        $userAdmin->assignRole('admin');

        $userBasic = User::create([
            'name' => 'Basic User',
            'email' => 'basic@email.com',
            'password' => bcrypt('password'),
        ]);

        $userBasic->player()->create([
            'username' => 'basic_user',
        ]);

        $userBasic->assignRole('user');
    }
}
