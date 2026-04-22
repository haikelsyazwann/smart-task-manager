<div class="flex flex-col h-full" x-data="{}">

    {{-- Board toolbar --}}
    <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 flex-shrink-0">
        <div class="flex-1 flex items-center gap-3">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ $tasksByStatus->flatten()->count() }} tasks
            </span>
            <span class="text-gray-200 dark:text-gray-700">|</span>
            <span class="text-xs text-gray-400">{{ $project->completionPercentage() }}% complete</span>
        </div>
        @if($canCreate)
        <button wire:click="openCreate" class="inline-flex items-center gap-1.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-3 py-1.5 rounded-lg transition-colors">
            + New Task
        </button>
        @endif
    </div>

    {{-- Inline create form --}}
    @if($showCreateForm)
    <div class="px-5 py-3 bg-violet-50 dark:bg-violet-900/20 border-b border-violet-200 dark:border-violet-800 flex-shrink-0">
        <form wire:submit="createTask" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Title *</label>
                <input wire:model="title" type="text" placeholder="Task title…" autofocus
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                @error('title') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Description</label>
                <input wire:model="description" type="text" placeholder="Short description…"
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Priority</label>
                <select wire:model="priority" class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Column</label>
                <select wire:model="createStatus" class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    @foreach($statuses as $s)
                    <option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Deadline</label>
                <input wire:model="deadline" type="date" class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" wire:loading.attr="disabled"
                        class="bg-violet-600 hover:bg-violet-700 text-white text-sm px-4 py-1.5 rounded-lg transition-colors font-medium disabled:opacity-60">
                    <span wire:loading.remove>Create</span>
                    <span wire:loading>Saving…</span>
                </button>
                <button type="button" wire:click="$set('showCreateForm', false)"
                        class="border border-gray-200 dark:border-gray-700 text-sm px-3 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Kanban columns --}}
    <div class="flex gap-4 p-5 overflow-x-auto flex-1 items-start">
        @php
            $colConfig = [
                'todo'        => ['label' => 'To Do',       'dot' => 'bg-blue-400',  'hdr' => 'text-blue-700 dark:text-blue-300',  'bg' => 'bg-blue-50 dark:bg-blue-950/30'],
                'in_progress' => ['label' => 'In Progress', 'dot' => 'bg-amber-400', 'hdr' => 'text-amber-700 dark:text-amber-300','bg' => 'bg-amber-50 dark:bg-amber-950/30'],
                'done'        => ['label' => 'Done',        'dot' => 'bg-emerald-400','hdr'=> 'text-emerald-700 dark:text-emerald-300','bg' => 'bg-emerald-50 dark:bg-emerald-950/30'],
            ];
        @endphp

        @foreach($statuses as $status)
        @php $colTasks = $tasksByStatus[$status] ?? collect(); $cfg = $colConfig[$status]; @endphp
        <div class="flex-shrink-0 w-72 flex flex-col rounded-xl border border-gray-200 dark:border-gray-800 {{ $cfg['bg'] }} overflow-hidden max-h-[calc(100vh-190px)]">
            {{-- Column header --}}
            <div class="flex items-center gap-2 px-3 py-2.5 bg-white/60 dark:bg-gray-900/60 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
                <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }}"></span>
                <span class="text-xs font-semibold {{ $cfg['hdr'] }} uppercase tracking-wide">{{ $cfg['label'] }}</span>
                <span class="ml-auto text-xs text-gray-400 bg-white dark:bg-gray-800 px-1.5 py-0.5 rounded-md border border-gray-200 dark:border-gray-700">{{ $colTasks->count() }}</span>
            </div>

            {{-- Cards --}}
            <div class="flex-1 overflow-y-auto p-2 space-y-2">
                @forelse($colTasks as $task)
                @php
                    $isOverdue = $task->deadline && $task->deadline < now() && $task->status !== 'done';
                    $doneSubs  = $task->subtasks->where('completed', true)->count();
                    $totalSubs = $task->subtasks->count();
                @endphp
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-3 shadow-sm hover:border-violet-300 dark:hover:border-violet-700 cursor-pointer transition-colors group"
                     wire:click="$dispatch('open-task-modal', { taskId: {{ $task->id }} })">
                    {{-- Priority badge --}}
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full uppercase tracking-wide flex-shrink-0
                            {{ $task->priority === 'high' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : ($task->priority === 'medium' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400') }}">
                            {{ $task->priority }}
                        </span>
                        @if($canCreate || $isAdmin || $isManager)
                        <button wire:click.stop="deleteTask({{ $task->id }})" onclick="event.stopPropagation()"
                                wire:confirm="Delete '{{ addslashes($task->title) }}'?"
                                class="opacity-0 group-hover:opacity-100 text-gray-300 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 transition text-xs flex-shrink-0">✕</button>
                        @endif
                    </div>

                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100 leading-snug mb-2">{{ $task->title }}</p>

                    @if($task->description)
                    <p class="text-xs text-gray-400 dark:text-gray-500 line-clamp-2 mb-2">{{ $task->description }}</p>
                    @endif

                    {{-- Footer meta --}}
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            {{-- Deadline --}}
                            @if($task->deadline)
                            <span class="text-[10px] {{ $isOverdue ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                                📅 {{ $task->deadline->format('M j') }}{{ $isOverdue ? ' ⚠' : '' }}
                            </span>
                            @endif
                            {{-- Subtasks --}}
                            @if($totalSubs > 0)
                            <span class="text-[10px] text-gray-400">☑ {{ $doneSubs }}/{{ $totalSubs }}</span>
                            @endif
                            {{-- Comments --}}
                            @if($task->comments->count() > 0)
                            <span class="text-[10px] text-gray-400">💬 {{ $task->comments->count() }}</span>
                            @endif
                        </div>
                        {{-- Assignee avatars --}}
                        <div class="flex -space-x-1.5">
                            @foreach($task->assignees->take(3) as $assignee)
                            <div class="w-5 h-5 rounded-full bg-violet-100 dark:bg-violet-900 border-2 border-white dark:border-gray-900 flex items-center justify-center text-[8px] font-semibold text-violet-700 dark:text-violet-300" title="{{ $assignee->name }}">
                                {{ strtoupper(substr($assignee->name, 0, 1)) }}
                            </div>
                            @endforeach
                            @if($task->assignees->count() > 3)
                            <div class="w-5 h-5 rounded-full bg-gray-100 dark:bg-gray-800 border-2 border-white dark:border-gray-900 flex items-center justify-center text-[8px] text-gray-500">+{{ $task->assignees->count() - 3 }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Move buttons --}}
                    <div class="flex gap-1 mt-2 opacity-0 group-hover:opacity-100 transition-opacity" wire:click.stop="">
                        @foreach($statuses as $s)
                        @if($s !== $status)
                        <button wire:click.stop="moveTask({{ $task->id }}, '{{ $s }}')"
                                class="text-[10px] px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-colors">
                            → {{ ucwords(str_replace('_',' ',$s)) }}
                        </button>
                        @endif
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-xs text-gray-400 dark:text-gray-600">
                    No tasks here
                </div>
                @endforelse

                {{-- Add task button per column --}}
                @if($canCreate)
                <button wire:click="openCreate('{{ $status }}')"
                        class="w-full text-left px-3 py-2 rounded-lg border border-dashed border-gray-200 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-600 hover:border-violet-400 dark:hover:border-violet-600 hover:text-violet-500 dark:hover:text-violet-400 transition-colors mt-1">
                    + Add task
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Task detail modal --}}
    <livewire:task-modal />
</div>
