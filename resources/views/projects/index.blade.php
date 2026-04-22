<x-app-layout>
    <x-slot name="header">
        <span class="text-gray-400">Workspace</span>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="font-medium">Projects</span>
    </x-slot>

    <div class="p-6 space-y-5">
        {{-- Header row --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold">Projects</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $projects->total() }} project{{ $projects->total() !== 1 ? 's' : '' }}</p>
            </div>
            @can('create', App\Models\Project::class)
            <button onclick="document.getElementById('create-project-modal').showModal()"
                    class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                + New Project
            </button>
            @endcan
        </div>

        {{-- Grid --}}
        @if($projects->isEmpty())
        <div class="text-center py-20 text-gray-400 dark:text-gray-500">
            <p class="text-4xl mb-3">📁</p>
            <p class="font-medium text-gray-600 dark:text-gray-300">No projects yet</p>
            <p class="text-sm mt-1">Create your first project to get started.</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($projects as $project)
            @php
                $total    = $project->tasks->count();
                $done     = $project->tasks->where('status', 'done')->count();
                $pct      = $total > 0 ? round($done / $total * 100) : 0;
                $overdue  = $project->tasks->where('status', '!=', 'done')
                                ->filter(fn($t) => $t->deadline && $t->deadline < now())->count();
            @endphp
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 hover:border-violet-300 dark:hover:border-violet-700 transition-colors group">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $project->color }}"></span>
                        <h3 class="font-semibold text-sm truncate">{{ $project->name }}</h3>
                    </div>
                    @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 opacity-0 group-hover:opacity-100 transition text-xs">✕</button>
                    </form>
                    @endcan
                </div>

                <p class="text-xs text-gray-400 dark:text-gray-500 mb-3 line-clamp-2 min-h-[2rem]">
                    {{ $project->description ?: 'No description.' }}
                </p>

                <div class="text-xs text-gray-400 dark:text-gray-500 mb-1 flex justify-between">
                    <span>{{ $done }}/{{ $total }} tasks</span>
                    @if($overdue > 0)
                    <span class="text-red-500">{{ $overdue }} overdue</span>
                    @endif
                    <span>{{ $pct }}%</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 mb-3">
                    <div class="h-1.5 rounded-full transition-all duration-500" style="width:{{ $pct }}%;background:{{ $project->color }}"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">{{ $project->team->name }}</span>
                    <a href="{{ route('projects.board', $project) }}"
                       class="text-xs text-violet-600 dark:text-violet-400 font-medium hover:underline">
                        Open board →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        {{ $projects->links() }}
        @endif
    </div>

    {{-- Create Project Modal --}}
    @can('create', App\Models\Project::class)
    <dialog id="create-project-modal" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-xl p-0 w-full max-w-md backdrop:bg-black/40">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="font-semibold text-sm text-gray-900 dark:text-gray-100">New Project</h2>
            <button onclick="document.getElementById('create-project-modal').close()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">✕</button>
        </div>
        <form method="POST" action="{{ route('projects.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Project name *</label>
                <input type="text" name="name" required placeholder="e.g. Mobile App Launch"
                    class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="What is this project about?"
                        class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Team *</label>
                    <select name="team_id" required class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="">Select team…</option>
                        @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Color</label>
                    <input type="color" name="color" value="#8b5cf6"
                        class="w-full h-9 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg cursor-pointer">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="document.getElementById('create-project-modal').close()"
                        class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-1.5 text-sm bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors font-medium">
                    Create Project
                </button>
            </div>
        </form>
    </dialog>
    @endcan
</x-app-layout>
