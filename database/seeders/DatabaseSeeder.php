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
        ]);

        $userAdmin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);

        $userAdmin->assignRole('admin');

        $userBasic = User::create([
            'name' => 'Basic User',
            'email' => 'basic@email.com',
            'password' => bcrypt('password'),
        ]);

        $userBasic->assignRole('user');
    }
}
