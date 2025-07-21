<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lesson::insert([
            ['title' => 'Keluarga', 'description' => 'test 1'],
            ['title' => 'Kata Kerja', 'description' => 'test 2'],
            ['title' => 'Kata Sifat', 'description' => 'test 3']
        ]);
    }
}
