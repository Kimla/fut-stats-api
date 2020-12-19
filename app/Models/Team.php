<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'user_id', 'updated_at', 'created_at',
    ];

    public function players()
    {
        return $this->belongsToMany('App\Models\Player', 'team_player');
    }
}
