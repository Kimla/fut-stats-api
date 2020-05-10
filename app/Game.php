<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];

    protected $casts = [
        'overtime' => 'boolean',
        'penalties' => 'boolean'
    ];

    public function weekendLeague()
    {
        return $this->belongsTo('App\WeekendLeague');
    }
}
