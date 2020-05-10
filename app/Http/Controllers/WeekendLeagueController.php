<?php

namespace App\Http\Controllers;

use App\WeekendLeague;
use Illuminate\Http\Request;

class WeekendLeagueController extends Controller
{
    public function index() {
        return WeekendLeague::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
    }

    public function get(WeekendLeague $weekendLeague) {
        return $weekendLeague->load('games');
    }

    public function store() {
        $weekendLeague = WeekendLeague::create([
            'title' => request('title'),
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'created',
            'item' => $weekendLeague,
        ], 200);
    }
}
