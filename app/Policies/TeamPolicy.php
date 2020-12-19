<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    use HandlesAuthorization;

    public function owner(User $user, Team $team)
    {
        return (int) $user->id === (int) $team->user_id
            ? Response::allow()
            : Response::deny('You do not own this team.');
    }
}
