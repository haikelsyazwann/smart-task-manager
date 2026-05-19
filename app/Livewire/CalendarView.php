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

        // Build calendar grid
        $firstDay    = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $lastDay     = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        $calendarDays = collect();

        for ($d = $firstDay->copy(); $d->lte($lastDay); $d->addDay()) {
            $calendarDays->push([
                'date'         => $d->copy(),
                'isCurrentMonth' => $d->month === $this->month,
                'isToday'      => $d->isToday(),
                'tasks'        => $tasks->get($d->format('Y-m-d'), collect()),
            ]);
        }

        return view('livewire.calendar-view', [
            'calendarDays' => $calendarDays,
            'monthLabel'   => $startOfMonth->format('F Y'),
        ]);
    }
}
