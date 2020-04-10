<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class GameController extends Controller
{
    public function index() {
        return Game::where('user_id', auth()->user()->id)->get();
    }

    public function store() {
        Game::create([
            'user_id' => auth()->user()->id,
            'outcome' => request('outcome'),
            'goals' =>  request('goals'),
            'conceded' =>  request('conceded'),
            'overtime' =>  request('overtime'),
            'penalties' =>  request('penalties'),
        ]);

        return response()->json([
            'message' => 'created',
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
}
