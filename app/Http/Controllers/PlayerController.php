<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function index()
    {
        return response()->json([
            'players' => Auth::user()->players,
        ], 200);
    }

    public function store()
    {
        $this->validateRequest();

        $player = Auth::user()->players()->create([
            'name' => request('name'),
        ]);

        return response()->json([
            'message' => __('players.created'),
            'player' => $player,
        ], 200);
    }

    public function update(Player $player)
    {
        $this->validateRequest();

        $this->authorize('owner', $player);

        $player->update([
            'name' => request('name'),
        ]);

        return response()->json([
            'message' => __('players.updated'),
            'player' => $player,
        ], 200);
    }

    public function destroy(Player $player)
    {
        $this->authorize('owner', $player);

        $player->delete();

        return response()->json([
            'message' => __('players.deleted'),
        ], 200);
    }

    protected function validateRequest()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
    }
}
