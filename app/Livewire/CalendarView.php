<?php

namespace App\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CalendarView extends Component
{
    public int $year;
    public int $month;

    public bool $showCreateModal   = false;
    public string $newTaskTitle    = '';
    public string $newTaskPriority = 'medium';
    public string $newTaskDeadline = '';
    public ?int $newTaskProjectId  = null;
    public array $userProjects     = [];

    public function mount(): void
    {
        $this->year  = now()->year;
        $this->month = now()->month;
    }

    public function prevMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->year  = $date->year;
        $this->month = $date->month;
    }

    public function nextMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->year  = $date->year;
        $this->month = $date->month;
    }

    public function openCreateModal(string $date): void
    {
        $user = Auth::user();
        $this->userProjects = $user->hasRole('admin')
            ? \App\Models\Project::orderBy('name')->get(['id','name'])->toArray()
            : $user->teams()->with('projects')->get()
                ->pluck('projects')->flatten()->unique('id')
                ->sortBy('name')->values()->toArray();

        $this->newTaskDeadline  = $date;
        $this->newTaskTitle     = '';
        $this->newTaskPriority  = 'medium';
        $this->newTaskProjectId = count($this->userProjects) === 1 ? $this->userProjects[0]['id'] : null;
        $this->showCreateModal  = true;
    }

    public function createTask(\App\Services\ActivityLogService $activity): void
    {
        $this->validate([
            'newTaskTitle'     => ['required', 'string', 'max:255'],
            'newTaskPriority'  => ['required', 'in:low,medium,high'],
            'newTaskDeadline'  => ['required', 'date'],
            'newTaskProjectId' => ['required', 'exists:projects,id'],
        ]);

        $project = \App\Models\Project::findOrFail($this->newTaskProjectId);

        $task = $project->tasks()->create([
            'creator_id' => Auth::id(),
            'title'      => $this->newTaskTitle,
            'priority'   => $this->newTaskPriority,
            'deadline'   => $this->newTaskDeadline,
            'status'     => 'todo',
            'position'   => (int) $project->tasks()->max('position') + 1,
        ]);

        $activity->log(Auth::id(), 'task.created', $task);

        $this->showCreateModal = false;
        $this->reset(['newTaskTitle', 'newTaskPriority', 'newTaskDeadline', 'newTaskProjectId']);
    }

    public function render()
    {
        $user = Auth::user();

        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        $tasksQuery = Task::with(['project', 'assignees'])
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [$startOfMonth, $endOfMonth]);

        if (!$user->hasRole('admin')) {
            $projectIds = $user->teams()->with('projects')->get()
                ->pluck('projects')->flatten()->pluck('id');
            $tasksQuery->whereIn('project_id', $projectIds)
                ->where(function ($q) use ($user) {
                    $q->where('creator_id', $user->id)
                      ->orWhereHas('assignees', fn($q) => $q->where('users.id', $user->id));
                });
        }

        $tasks = $tasksQuery->get()->groupBy(fn($t) => $t->deadline->format('Y-m-d'));

        $firstDay     = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $lastDay      = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        $calendarDays = collect();

        for ($d = $firstDay->copy(); $d->lte($lastDay); $d->addDay()) {
            $calendarDays->push([
                'date'           => $d->copy(),
                'isCurrentMonth' => $d->month === $this->month,
                'isToday'        => $d->isToday(),
                'tasks'          => $tasks->get($d->format('Y-m-d'), collect()),
            ]);
        }

        return view('livewire.calendar-view', [
            'calendarDays' => $calendarDays,
            'monthLabel'   => $startOfMonth->format('F Y'),
        ]);
    }
}
