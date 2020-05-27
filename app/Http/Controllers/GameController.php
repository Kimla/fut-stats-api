<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function store()
    {
        $game = Game::create([
            'weekend_league_id' => request('weekend_league_id'),
            'user_id' => auth()->user()->id,
            'outcome' => request('outcome'),
            'goals' => request('goals'),
            'conceded' => request('conceded'),
            'overtime' => request('overtime'),
            'penalties' => request('penalties'),
        ]);

        $this->syncPlayerStatistics($game, request('playerStatistics'));

        return response()->json([
            'message' => 'created',
            'id' => $game->id,
        ], 200);
    }

    public function update(Game $game)
    {
        $game->update([
            'outcome' => request('outcome'),
            'goals' => request('goals'),
            'conceded' => request('conceded'),
            'overtime' => request('overtime'),
            'penalties' => request('penalties'),
        ]);

        $this->syncPlayerStatistics($game, request('playerStatistics'));

        return response()->json([
            'message' => 'updated',
        ], 200);
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return response()->json([
            'message' => 'deleted.',
        ], 200);
    }

    protected function syncPlayerStatistics(Game $game, array $playerStatistics)
    {
        collect($playerStatistics)->each(function ($item) use ($game) {
            $data = [
                'player_id' => $item['player_id'],
                'rating' => $item['rating'],
                'goals' => $item['goals'],
                'assists' => $item['assists'],
            ];

            $game->playerStatistics()->updateOrCreate(
                ['player_id' => $item['player_id']],
                $data
            );
        });
    }
}
