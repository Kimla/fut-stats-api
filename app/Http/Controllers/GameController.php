<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class GameController extends Controller
{
    public function store() {
        $game = Game::create([
            'weekend_league_id' => request('weekend_league_id'),
            'user_id' => auth()->user()->id,
            'outcome' => request('outcome'),
            'goals' =>  request('goals'),
            'conceded' =>  request('conceded'),
            'overtime' =>  request('overtime'),
            'penalties' =>  request('penalties'),
        ]);

        return response()->json([
            'message' => 'created',
            'id' => $game->id
        ], 200);
    }

    public function update(Game $game) {
        $game->update([
            'outcome' => request('outcome'),
            'goals' =>  request('goals'),
            'conceded' =>  request('conceded'),
            'overtime' =>  request('overtime'),
            'penalties' =>  request('penalties'),
        ]);

        return response()->json([
            'message' => 'updated'
        ], 200);
    }

    public function destroy(Game $game) {
        $game->delete();

        return response()->json([
            'message' => 'deleted.',
        ], 200);
    }
}
