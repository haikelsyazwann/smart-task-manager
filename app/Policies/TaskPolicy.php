<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user): bool { return true; }

    public function update(User $user, Task $task): bool
    {
        return $user->hasRole('admin') ||
            $user->hasRole('manager') ||
            $task->creator_id === $user->id ||
            $task->assignees->contains($user->id);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasRole('admin') ||
            ($user->hasRole('manager') && $task->project->team->owner_id === $user->id) ||
            $task->creator_id === $user->id;
    }
}
