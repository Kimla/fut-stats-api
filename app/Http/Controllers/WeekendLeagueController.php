<?php

namespace App\Http\Controllers;

use App\WeekendLeague;
use Illuminate\Http\Request;

class WeekendLeagueController extends Controller
{
    public function index()
    {
        return WeekendLeague::where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get()
            ->each(function ($item, $key) {
                $item->withScore();

                return $item;
            })
            ->all();
    }

    public function get(WeekendLeague $weekendLeague)
    {
        return $weekendLeague->load('games', 'games.playerStatistics', 'games.playerStatistics.player');
    }

    public function store()
    {
        $weekendLeague = WeekendLeague::create([
            'title' => request('title'),
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'created',
            'item' => $weekendLeague->withScore(),
        ], 200);
    }
}
