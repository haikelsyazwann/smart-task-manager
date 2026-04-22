<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function create(User $user): bool  { return $user->hasRole('admin') || $user->hasRole('manager'); }
    public function update(User $user, Team $team): bool { return $user->hasRole('admin') || $team->owner_id === $user->id; }
    public function delete(User $user, Team $team): bool { return $user->hasRole('admin') || $team->owner_id === $user->id; }
}
