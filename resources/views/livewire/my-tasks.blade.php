<div class="p-6 space-y-4">
    {{-- Header + filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <h1 class="text-lg font-semibold flex-1 min-w-full sm:min-w-0">My Tasks</h1>

        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search tasks…"
                   class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg pl-8 pr-3 py-1.5 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-violet-500">
        </div>

        <select wire:model.live="filterStatus"
                class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            <option value="">All statuses</option>
            <option value="todo">To Do</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>

        <select wire:model.live="filterPriority"
                class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            <option value="">All priorities</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>

        <select wire:model.live="filterProject"
                class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            <option value="">All projects</option>
            @foreach($projects as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        @if($search || $filterStatus || $filterPriority || $filterProject)
        <button wire:click="$set('search','');$set('filterStatus','');$set('filterPriority','');$set('filterProject','')"
                class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            Clear filters
        </button>
        @endif
    </div>

    {{-- Stats bar --}}
    <div class="flex gap-3">
        @php
            $total    = $tasks->total();
            $overdue  = $tasks->getCollection()->filter(fn($t) => $t->deadline && $t->deadline < now() && $t->status !== 'done')->count();
        @endphp
        <span class="text-xs text-gray-500 dark:text-gray-400">
            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $total }}</span> task{{ $total !== 1 ? 's' : '' }}
        </span>
        @if($overdue > 0)
        <span class="text-xs text-red-600 font-medium">⚠ {{ $overdue }} overdue</span>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    @php
                        $th = fn($col, $label) =>
                            '<th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 select-none whitespace-nowrap" wire:click="sort(\''.$col.'\')">'
                            .$label
                            .($this->sortBy===$col ? ($this->sortDir==='asc' ? ' <span class="text-violet-500">↑</span>' : ' <span class="text-violet-500">↓</span>') : '')
                            .'</th>';
                    @endphp
                    {!! $th('title','Task') !!}
                    {!! $th('project','Project') !!}
                    {!! $th('priority','Priority') !!}
                    {!! $th('status','Status') !!}
                    {!! $th('deadline','Deadline') !!}
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Progress</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($tasks as $task)
                @php
                    $isOverdue = $task->deadline && $task->deadline < now() && $task->status !== 'done';
                    $doneSubs  = $task->subtasks->where('completed', true)->count();
                    $totalSubs = $task->subtasks->count();
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-4 py-3 max-w-[200px]">
                        <button wire:click="$dispatch('open-task-modal', { taskId: {{ $task->id }} })"
                                class="text-left font-medium text-gray-800 dark:text-gray-100 hover:text-violet-600 dark:hover:text-violet-400 transition-colors truncate block w-full">
                            {{ $task->title }}
                        </button>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $task->project->color ?? '#8b5cf6' }}"></span>
                            <span class="text-gray-600 dark:text-gray-400 text-xs truncate max-w-[120px]">{{ $task->project->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full uppercase tracking-wide
                            {{ $task->priority === 'high'   ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
                            : ($task->priority === 'medium' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400'
                            : 'bg-gray-100 dark:bg-gray-800 text-gray-500') }}">
                            {{ $task->priority }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                            {{ $task->status === 'done'        ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'
                            : ($task->status === 'in_progress' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400'
                            : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400') }}">
                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs whitespace-nowrap {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $task->deadline ? $task->deadline->format('M j, Y').($isOverdue ? ' ⚠' : '') : '—' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($totalSubs > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-20 bg-gray-100 dark:bg-gray-800 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-violet-500 transition-all" style="width:{{ round($doneSubs / $totalSubs * 100) }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $doneSubs }}/{{ $totalSubs }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="$dispatch('open-task-modal', { taskId: {{ $task->id }} })"
                                class="text-xs text-violet-600 dark:text-violet-400 hover:underline font-medium whitespace-nowrap">
                            View →
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">No tasks found</p>
                            <p class="text-xs text-gray-400">{{ $search || $filterStatus || $filterPriority || $filterProject ? 'Try adjusting your filters.' : 'Tasks assigned to you will appear here.' }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($tasks->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
            {{ $tasks->links() }}
        </div>
        @endif
    </div>

    {{-- Task modal --}}
    <livewire:task-modal />
</div>
