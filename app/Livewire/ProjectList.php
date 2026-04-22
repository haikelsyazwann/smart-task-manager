<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectList extends Component
{
    public function render()
    {
        $user     = Auth::user();
        $projects = $user->hasRole('admin')
            ? Project::with(['team', 'tasks'])->latest()->take(6)->get()
            : Project::whereHas('team.members', fn($q) => $q->where('users.id', $user->id))
                ->with(['team', 'tasks'])->latest()->take(6)->get();

        return view('livewire.project-list', compact('projects'));
    }
}
