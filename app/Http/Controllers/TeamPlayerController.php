<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeamPlayer;

class TeamPlayerController extends Controller
{
    public function index() {
        return TeamPlayer::where('user_id', auth()->user()->id)->get();
    }

    public function store() {
        $player = TeamPlayer::create([
            'name' => request('name'),
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'created',
            'player' => $player
        ], 200);
    }

    public function destroy(TeamPlayer $teamPlayer) {
        $teamPlayer->delete();

        return response()->json([
            'message' => 'deleted.',
        ], 200);
    }
}
