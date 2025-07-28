<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    //

    public function addExp(Request $request)
    {
        // Validate request
        $validate = $request->validate([
            'player_id' => 'required|exists:players,id',
            'exp' => 'required|integer|min:1',
        ]);

        if(!$validate){
            return response()->json([
                'message'=>'failed'
            ]);
        }

        // Get data
        $player_id = $request->input('player_id');
        $expToAdd = $request->input('exp');

        // Find player
        $player = Player::find($player_id);

        if (!$player) {
            return response()->json(['message' => 'Player not found.'], 404);
        }

        // Add EXP
        $player->exp += $expToAdd;

        // (Optional) Handle level up logic
        $expForNextLevel = $player->level * 100; // example: 100 EXP per level
        while ($player->exp >= $expForNextLevel) {
            $player->exp -= $expForNextLevel;
            $player->level += 1;
            $expForNextLevel = $player->level * 100;
        }

        // Save changes
        $player->save();

        // Return response
        return response()->json([
            'message' => 'EXP added successfully.',
            'player' => $player
        ]);
    }
}
