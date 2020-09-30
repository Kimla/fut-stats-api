<?php

namespace App\Policies;

use App\Player;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PlayerPolicy
{
    use HandlesAuthorization;

    public function owner(User $user, Player $player)
    {
        return (int) $user->id === (int) $player->user_id
            ? Response::allow()
            : Response::deny('You do not own this player.');
    }
}
