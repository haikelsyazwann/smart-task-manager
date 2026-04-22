<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projects = $user->hasRole('admin')
            ? Project::with(['team', 'tasks'])->latest()->paginate(12)
            : Project::whereHas('team.members', fn($q) => $q->where('users.id', $user->id))
                ->with(['team', 'tasks'])->latest()->paginate(12);

        $teams = $user->hasRole('admin')
            ? Team::all()
            : $user->teams;

        return view('projects.index', compact('projects', 'teams'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Project::class);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'team_id'     => ['required', 'exists:teams,id'],
            'color'       => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $project = Project::create([...$data, 'owner_id' => Auth::id(), 'color' => $data['color'] ?? '#8b5cf6']);

        (new ActivityLogService)->log(Auth::id(), 'project.created', $project);

        return redirect()->route('projects.board', $project)->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);
        return redirect()->route('projects.board', $project);
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color'       => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $project->update($data);
        (new ActivityLogService)->log(Auth::id(), 'project.updated', $project);

        return back()->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
