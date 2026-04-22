<?php

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('project.{projectId}', function ($user, int $projectId) {
    return Project::whereKey($projectId)
        ->whereHas('team.members', fn ($query) => $query->where('users.id', $user->id))
        ->exists();
});

Broadcast::channel('task.{taskId}', function ($user, int $taskId) {
    return Task::whereKey($taskId)
        ->whereHas('project.team.members', fn ($query) => $query->where('users.id', $user->id))
        ->exists();
});
