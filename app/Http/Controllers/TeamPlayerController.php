<?php

namespace App\Http\Controllers;

use App\TeamPlayer;
use DB;
use Illuminate\Http\Request;

class TeamPlayerController extends Controller
{
    public function index()
    {
        return TeamPlayer::where('user_id', auth()->user()->id)
            ->orderBy('sort_order', 'ASC')
            ->get();
    }

    public function store()
    {
        $player = TeamPlayer::create([
            'name' => request('name'),
            'user_id' => auth()->user()->id,
            'sort_order' => 255,
        ]);

        return response()->json([
            'message' => 'created',
            'player' => $player,
        ], 200);
    }

    public function destroy(TeamPlayer $teamPlayer)
    {
        $teamPlayer->delete();

        return response()->json([
            'message' => 'deleted.',
        ], 200);
    }

    public function updateSortOrder()
    {
        $players = request('players');

        DB::transaction(function () use ($players) {
            foreach ($players as $player) {
                \DB::table('team_players')
                    ->where('id', '=', $player['id'])
                    ->update([
                        'sort_order' => $player['sort_order'],
                ]);
            }
        });

        return response()->json([
            'message' => 'updated.',
        ], 200);
    }
}
