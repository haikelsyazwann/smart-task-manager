<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $user = Auth::user();
        $base = $user->hasRole('admin')
            ? Task::query()
            : Task::query()->whereHas('project.team.members', fn($q) => $q->where('users.id', $user->id));

        // Tasks completed per day for last 7 days
        $last7 = collect(range(6, 0))->map(function ($daysAgo) use ($base) {
            $date = now()->subDays($daysAgo)->toDateString();
            return [
                'date'  => now()->subDays($daysAgo)->format('M j'),
                'count' => (clone $base)
                    ->where('status', Task::STATUS_DONE)
                    ->whereDate('updated_at', $date)
                    ->count(),
            ];
        });

        return view('livewire.dashboard-stats', [
            'totalTasks'      => (clone $base)->count(),
            'completedTasks'  => (clone $base)->where('status', Task::STATUS_DONE)->count(),
            'inProgressTasks' => (clone $base)->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'overdueTasks'    => (clone $base)->where('deadline', '<', now())->where('status', '!=', Task::STATUS_DONE)->count(),
            'todoTasks'       => (clone $base)->where('status', Task::STATUS_TODO)->count(),
            'chartDays'       => $last7->pluck('date'),
            'chartCounts'     => $last7->pluck('count'),
        ]);
    }
}
