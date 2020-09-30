<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'user_id', 'updated_at', 'created_at',
    ];

    public function teams()
    {
        return $this->belongsToMany('App\Team', 'team_player');
    }
}
