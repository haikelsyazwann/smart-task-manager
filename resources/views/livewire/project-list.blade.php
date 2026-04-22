<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @forelse($projects as $project)
    @php
        $total = $project->tasks->count();
        $done  = $project->tasks->where('status','done')->count();
        $pct   = $total > 0 ? round($done / $total * 100) : 0;
    @endphp
    <a href="{{ route('projects.board', $project) }}"
       class="group flex flex-col gap-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 hover:border-violet-300 dark:hover:border-violet-700 hover:shadow-sm transition-all">
        <div class="flex items-start justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 mt-0.5" style="background:{{ $project->color ?? '#8b5cf6' }}"></span>
                <span class="font-semibold text-sm text-gray-800 dark:text-gray-100 truncate group-hover:text-violet-700 dark:group-hover:text-violet-300 transition-colors">{{ $project->name }}</span>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex-shrink-0">
                {{ $project->team->name }}
            </span>
        </div>
        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-[11px] text-gray-400">
                <span>{{ $done }} of {{ $total }} tasks done</span>
                <span class="font-medium {{ $pct == 100 ? 'text-emerald-600' : 'text-gray-500' }}">{{ $pct }}%</span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                <div class="h-1.5 rounded-full transition-all duration-300" style="width:{{ $pct }}%;background:{{ $project->color ?? '#8b5cf6' }}"></div>
            </div>
        </div>
    </a>
    @empty
    <div class="col-span-2 flex flex-col items-center justify-center py-12 text-center bg-white dark:bg-gray-900 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl">
        <div class="w-10 h-10 rounded-full bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">No projects yet</p>
        <a href="{{ route('projects.index') }}" class="text-xs text-violet-600 dark:text-violet-400 hover:underline font-medium">Create your first project →</a>
    </div>
    @endforelse
</div>
