<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerStatistic extends Model
{
    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function player()
    {
        return $this->belongsTo('App\TeamPlayer')->withTrashed();
    }
}
