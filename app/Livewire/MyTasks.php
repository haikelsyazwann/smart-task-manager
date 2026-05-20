<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyTasks extends Component
{
    use WithPagination;

    public string $filterStatus   = '';
    public string $filterPriority = '';
    public string $filterProject  = '';
    public string $search         = '';
    public string $sortBy         = 'deadline';
    public string $sortDir        = 'asc';

    protected $queryString = ['filterStatus', 'filterPriority', 'filterProject', 'search'];
    protected $listeners   = ['task-updated' => '$refresh', 'task-deleted' => '$refresh'];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function sort(string $col): void
    {
        $this->sortDir = ($this->sortBy === $col && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy  = $col;
    }

    public function clearFilters(): void
        {
            $this->search         = '';
            $this->filterStatus   = '';
            $this->filterPriority = '';
            $this->filterProject  = '';
            $this->resetPage();
        }

    public function render()
    {
        $user = Auth::user();

        $query = Task::with(['project', 'assignees', 'subtasks'])
            ->whereHas('assignees', fn($q) => $q->where('users.id', $user->id))
            ->when($this->search,         fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->filterStatus,   fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterPriority, fn($q) => $q->where('priority', $this->filterPriority))
            ->when($this->filterProject,  fn($q) => $q->where('project_id', $this->filterProject))
            ->orderBy($this->sortBy === 'project' ? 'project_id' : $this->sortBy, $this->sortDir);

        $projects = Task::whereHas('assignees', fn($q) => $q->where('users.id', $user->id))
            ->with('project')->get()->pluck('project')->unique('id');

        return view('livewire.my-tasks', [
            'tasks'    => $query->paginate(15),
            'projects' => $projects,
        ]);
    }
}
