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
            ['title' => 'Keluarga', 'description' => 'test 1', 'required_level' => 1],
            ['title' => 'Kata Kerja', 'description' => 'test 2', 'required_level' => 2],
            ['title' => 'Kata Sifat', 'description' => 'test 3', 'required_level' => 3],
            // ['title' => 'Kata Ganti', 'description' => 'test 4', 'required_level' => 4],
            // ['title' => 'Kata Tanya', 'description' => 'test 5', 'required_level' => 5],
            // ['title' => 'Kata Sendi', 'description' => 'test 6', 'required_level' => 6],
            // ['title' => 'Kata Hubung', 'description' => 'test 7', 'required_level' => 7],
            // ['title' => 'Kata Bilangan', 'description' => 'test 8', 'required_level' => 8],
        ]);
    }
}
