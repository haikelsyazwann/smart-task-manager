<?php

namespace App\Livewire;

use App\Events\TaskUpdated;
use App\Models\Project;
use App\Models\Task;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class TaskBoard extends Component
{
    public Project $project;

    public string $title        = '';
    public string $description  = '';
    public string $priority     = 'medium';
    public ?string $deadline    = null;
    public string $createStatus = 'todo';
    public bool $showCreateForm = false;

    protected $rules = [
        'title'        => ['required', 'string', 'max:255'],
        'description'  => ['nullable', 'string'],
        'priority'     => ['required', 'in:low,medium,high'],
        'deadline'     => ['nullable', 'date'],
        'createStatus' => ['required', 'in:todo,in_progress,done'],
    ];

    protected $listeners = [
        'task-updated' => '$refresh',
        'task-deleted' => '$refresh',
    ];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function openCreate(string $status = 'todo'): void
    {
        $this->createStatus   = $status;
        $this->showCreateForm = true;
        $this->resetExcept('project', 'createStatus', 'showCreateForm');
        $this->priority = 'medium';
    }

    public function createTask(ActivityLogService $activity): void
    {
        $this->validate();

        $task = $this->project->tasks()->create([
            'creator_id'  => Auth::id(),
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority,
            'deadline'    => $this->deadline ?: null,
            'status'      => $this->createStatus,
            'position'    => (int) $this->project->tasks()->max('position') + 1,
        ]);

        $activity->log(Auth::id(), 'task.created', $task);
        TaskUpdated::dispatch($task);

        $this->showCreateForm = false;
        $this->reset(['title', 'description', 'deadline']);
        $this->priority = 'medium';
    }

    public function moveTask(int $taskId, string $status, ActivityLogService $activity): void
    {
        abort_unless(in_array($status, Task::statuses(), true), 422);
        $task = $this->project->tasks()->findOrFail($taskId);
        Gate::authorize('update', $task);

        $task->update(['status' => $status]);
        $activity->log(Auth::id(), 'task.status_changed', $task, ['status' => $status]);
        TaskUpdated::dispatch($task);
    }

    public function deleteTask(int $taskId, ActivityLogService $activity): void
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        Gate::authorize('delete', $task);
        $activity->log(Auth::id(), 'task.deleted', $task);
        $task->delete();
        $this->dispatch('task-deleted');
    }

    public function render()
    {
        return view('livewire.task-board', [
            'tasksByStatus' => $this->project->tasks()
                ->with(['assignees', 'subtasks', 'comments'])
                ->orderBy('position')
                ->get()
                ->groupBy('status'),
            'statuses'      => Task::statuses(),
            'canCreate'     => Auth::user()->can('create', Task::class),
            'isAdmin'       => Auth::user()->hasRole('admin'),
            'isManager'     => Auth::user()->hasRole('manager'),
        ]);
    }
}
