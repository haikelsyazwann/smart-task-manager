<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = $user->hasRole('admin')
            ? Team::with(['owner', 'members', 'projects'])->latest()->get()
            : $user->teams()->with(['owner', 'members', 'projects'])->get();

        $allUsers = User::orderBy('name')->get();
        return view('teams.index', compact('teams', 'allUsers'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Team::class);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'members'     => ['nullable', 'array'],
            'members.*'   => ['exists:users,id'],
        ]);

        $team = Team::create([
            'owner_id'    => Auth::id(),
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $team->members()->attach(Auth::id(), ['role' => 'manager']);

        if (!empty($data['members'])) {
            foreach (array_diff($data['members'], [Auth::id()]) as $uid) {
                $team->members()->attach($uid, ['role' => 'member']);
            }
        }

        (new ActivityLogService)->log(Auth::id(), 'team.created', $team);

        return redirect()->route('teams.index')->with('success', 'Team created.');
    }

    public function update(Request $request, Team $team)
    {
        Gate::authorize('update', $team);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'members'     => ['nullable', 'array'],
            'members.*'   => ['exists:users,id'],
        ]);

        $team->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['members'])) {
            $syncData = [];
            foreach ($data['members'] as $uid) {
                $syncData[$uid] = ['role' => $uid == $team->owner_id ? 'manager' : 'member'];
            }
            $team->members()->sync($syncData);
        }

        return back()->with('success', 'Team updated.');
    }

    public function destroy(Team $team)
    {
        Gate::authorize('delete', $team);
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted.');
    }
}
