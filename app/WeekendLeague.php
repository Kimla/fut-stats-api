<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekendLeague extends Model
{
    protected $guarded = [];

    public function withScore()
    {
        $this->attributes['score'] = $this->games->reduce(function ($score, $game) {
            $game->outcome === 'win' ? $score['wins']++ : $score['losses']++;

            return $score;
        }, ['wins' => 0, 'losses' => 0]);
        
        return $this;
    }

    public function games()
    {
        return $this->hasMany('App\Game');
    }
}
