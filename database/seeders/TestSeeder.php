<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
        $player = Player::create([
            'user_id' => '1',
            'username' => 'testuser',
            'level' => 1,
            'exp' => 0,
            'avatar' => 'default_avatar.png'
        ]);

        $player_lesson  = $player->lessons()->create([
            'lesson_id' => 1,
            'progress' => 0,
            'completed' => false,
            'completed_at' => null
        ]);
    }
}
