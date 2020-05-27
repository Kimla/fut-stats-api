<?php

namespace App\Http\Controllers;

use App\PlayerPriceWatch;
use Illuminate\Http\Request;

class PlayerPriceWatchController extends Controller
{
    public function index()
    {
        return PlayerPriceWatch::where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $player = PlayerPriceWatch::create([
            'user_id' => auth()->user()->id,
            'futbin_id' => request('futbin_id'),
            'min_amount' => request('min_amount'),
            'max_amount' => request('max_amount'),
            'title' => request('title'),
        ]);

        return response()->json([
            'message' => 'created',
            'item' => $player,
        ], 200);
    }

    public function show(PlayerPriceWatch $playerPriceWatch)
    {
        return $playerPriceWatch;
    }

    public function destroy(PlayerPriceWatch $playerPriceWatch)
    {
        $playerPriceWatch->delete();

        return response()->json([
            'message' => 'deleted.',
        ], 200);
    }
}
