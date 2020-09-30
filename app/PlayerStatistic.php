<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerStatistic extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rating' => 'decimal(10,1)',
    ];

    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function player()
    {
        return $this->belongsTo('App\Player')->withTrashed();
    }
}
