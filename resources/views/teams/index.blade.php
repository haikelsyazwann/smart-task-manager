<x-app-layout>
    <x-slot name="header">
        <span class="text-gray-400">Management</span>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="font-medium">Teams</span>
    </x-slot>

    <div class="p-6 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold">Teams</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $teams->count() }} team{{ $teams->count() !== 1 ? 's' : '' }}</p>
            </div>
            @can('create', App\Models\Team::class)
            <button onclick="document.getElementById('create-team-modal').showModal()"
                    class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                + New Team
            </button>
            @endcan
        </div>

        @if($teams->isEmpty())
        <div class="text-center py-20 text-gray-400 dark:text-gray-500">
            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
            </div>
            <p class="font-medium text-gray-600 dark:text-gray-300">No teams yet</p>
            <p class="text-sm mt-1">Create your first team to get started.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($teams as $team)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 group">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $team->name }}</h3>
                        @if($team->description)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $team->description }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">Owner: {{ $team->owner->name }}</p>
                    </div>
                    @can('update', $team)
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="openEditTeam({{ $team->id }}, '{{ addslashes($team->name) }}', '{{ addslashes($team->description ?? '') }}', {!! $team->members->pluck('id')->toJson() !!})"
                                class="text-xs text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 p-1.5 rounded transition-colors" title="Edit team">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('teams.destroy', $team) }}" onsubmit="return confirm('Delete team {{ addslashes($team->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-500 p-1.5 rounded transition-colors" title="Delete team">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>

                {{-- Members --}}
                <div class="mb-3">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Members ({{ $team->members->count() }})</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($team->members as $member)
                        <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 rounded-full px-2.5 py-1 border border-gray-200 dark:border-gray-700">
                            <x-avatar :avatar="$member->avatar ?? 'boy1'" size="xs" />
                            <span class="text-[11px] font-medium text-gray-700 dark:text-gray-300">{{ $member->name }}</span>
                            @if($member->pivot->role === 'manager')
                            <span class="text-[9px] bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-400 px-1 rounded">mgr</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Projects --}}
                @if($team->projects->count() > 0)
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Projects</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach($team->projects as $proj)
                        <a href="{{ route('projects.board', $proj) }}"
                           class="flex items-center gap-1 text-[11px] text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded px-2 py-0.5 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-300 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $proj->color }}"></span>
                            {{ $proj->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Create Team Modal --}}
    @can('create', App\Models\Team::class)
    <dialog id="create-team-modal" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-xl p-0 w-full max-w-md backdrop:bg-black/40">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="font-semibold text-sm text-gray-900 dark:text-gray-100">New Team</h2>
            <button onclick="document.getElementById('create-team-modal').close()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">✕</button>
        </div>
        <form method="POST" action="{{ route('teams.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Team name *</label>
                <input type="text" name="name" required placeholder="e.g. Frontend Squad"
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="What does this team work on?"
                          class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Add members</label>
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-2 max-h-48 overflow-y-auto bg-white dark:bg-gray-800 space-y-1">
                    @foreach($allUsers as $u)
                    <label class="flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="checkbox" name="members[]" value="{{ $u->id }}" class="accent-violet-600">
                        <x-avatar :avatar="$u->avatar ?? 'boy1'" size="xs" />
                        <span class="text-xs text-gray-700 dark:text-gray-300">{{ $u->name }}</span>
                        <span class="ml-auto text-[10px] text-gray-400 capitalize">{{ $u->getRoleNames()->first() }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="document.getElementById('create-team-modal').close()"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-1.5 text-sm bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors font-medium">Create Team</button>
            </div>
        </form>
    </dialog>
    @endcan

    {{-- Edit Team Modal --}}
    <dialog id="edit-team-modal" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-xl p-0 w-full max-w-md backdrop:bg-black/40">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="font-semibold text-sm text-gray-900 dark:text-gray-100">Edit Team</h2>
            <button onclick="document.getElementById('edit-team-modal').close()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">✕</button>
        </div>
        <form method="POST" id="edit-team-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Team name *</label>
                <input type="text" name="name" id="edit-team-name" required
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Description</label>
                <textarea name="description" id="edit-team-desc" rows="2"
                          class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Members</label>
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-2 max-h-48 overflow-y-auto bg-white dark:bg-gray-800 space-y-1">
                    @foreach($allUsers as $u)
                    <label class="flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="checkbox" name="members[]" value="{{ $u->id }}"
                               class="edit-member-checkbox accent-violet-600"
                               data-user-id="{{ $u->id }}">
                        <x-avatar :avatar="$u->avatar ?? 'boy1'" size="xs" />
                        <span class="text-xs text-gray-700 dark:text-gray-300">{{ $u->name }}</span>
                        <span class="ml-auto text-[10px] text-gray-400 capitalize">{{ $u->getRoleNames()->first() }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="document.getElementById('edit-team-modal').close()"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-1.5 text-sm bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors font-medium">Save Changes</button>
            </div>
        </form>
    </dialog>

    <script>
    function openEditTeam(id, name, desc, memberIds) {
        document.getElementById('edit-team-name').value = name;
        document.getElementById('edit-team-desc').value = desc;
        document.getElementById('edit-team-form').action = '/teams/' + id;

        // Reset then pre-check current members
        document.querySelectorAll('.edit-member-checkbox').forEach(cb => {
            cb.checked = memberIds.includes(parseInt(cb.dataset.userId));
        });

        document.getElementById('edit-team-modal').showModal();
    }
    </script>
</x-app-layout>
