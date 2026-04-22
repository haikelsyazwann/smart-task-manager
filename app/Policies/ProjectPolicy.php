<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool { return true; }

    public function view(User $user, Project $project): bool
    {
        return $user->hasRole('admin') ||
            $project->team->members->contains($user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $project->owner_id === $user->id);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $project->owner_id === $user->id);
    }
}
