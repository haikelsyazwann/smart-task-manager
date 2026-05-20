<div class="p-6 h-full flex flex-col">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $monthLabel }}</h1>
        <div class="flex items-center gap-2">
            <button wire:click="prevMonth"
                class="p-1.5 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </button>
            <button wire:click="$set('month', {{ now()->month }}); $set('year', {{ now()->year }})"
                class="px-3 py-1 text-xs font-medium rounded-md border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                Today
            </button>
            <button wire:click="nextMonth"
                class="p-1.5 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-4 mb-4">
        <span class="text-xs text-gray-400 dark:text-gray-500">Priority:</span>
        <span class="flex items-center gap-1 text-xs text-red-600 dark:text-red-400"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> High</span>
        <span class="flex items-center gap-1 text-xs text-amber-600 dark:text-amber-400"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> Medium</span>
        <span class="flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> Low</span>
        <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 ml-4"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Done</span>
    </div>

    {{-- Day headers --}}
    <div class="grid grid-cols-7 mb-1">
        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
        <div class="text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 py-1">{{ $day }}</div>
        @endforeach
    </div>

    {{-- Calendar grid --}}
    <div class="grid grid-cols-7 flex-1 border-l border-t border-gray-200 dark:border-gray-800">
        @foreach($calendarDays as $day)
        <div class="border-r border-b border-gray-200 dark:border-gray-800 p-1 min-h-[110px] cursor-pointer
            {{ !$day['isCurrentMonth'] ? 'bg-gray-50 dark:bg-gray-900/50' : ($day['isToday'] ? 'bg-violet-50 dark:bg-violet-950/30' : 'bg-white dark:bg-gray-900') }}"
            wire:click="openCreateModal('{{ $day['date']->format('Y-m-d') }}')">

            {{-- Date number + task count badge --}}
            <div class="flex items-center justify-between mb-1">
                <span class="w-6 h-6 flex items-center justify-center text-xs font-medium rounded-full
                    {{ $day['isToday'] ? 'bg-violet-600 text-white' : ($day['isCurrentMonth'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-300 dark:text-gray-600') }}">
                    {{ $day['date']->day }}
                </span>
                @if($day['tasks']->count() > 0)
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 leading-none">
                    {{ $day['tasks']->count() }}
                </span>
                @endif
            </div>

            {{-- Tasks --}}
            <div class="space-y-0.5">
                @foreach($day['tasks']->take(3) as $task)
                <div class="relative group" wire:click.stop>
                    <button
                        wire:click.stop="$dispatch('open-task-modal', { taskId: {{ $task->id }} })"
                        class="block w-full text-left truncate text-[11px] px-1.5 py-0.5 rounded font-medium leading-tight
                            {{ $task->status === 'done'
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 line-through'
                                : (($task->deadline < now())
                                    ? 'bg-red-200 text-red-800 dark:bg-red-900/60 dark:text-red-200 ring-1 ring-red-400'
                                    : ($task->priority === 'high'
                                        ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'
                                        : ($task->priority === 'medium'
                                            ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'
                                            : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'))) }}">
                        {{ $task->title }}
                    </button>

                    {{-- Tooltip --}}
                    <div class="absolute z-50 left-0 top-full mt-1 w-48 bg-gray-900 dark:bg-gray-700 text-white text-[11px] rounded-lg shadow-xl p-2.5 space-y-1.5
                                invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 pointer-events-none">
                        <p class="font-semibold truncate">{{ $task->title }}</p>
                        <div class="border-t border-gray-700 dark:border-gray-500 pt-1.5 space-y-1">
                            <p class="flex items-center gap-1.5">
                                <span class="text-gray-400">Project:</span>
                                <span class="truncate">{{ $task->project->name }}</span>
                            </p>
                            <p class="flex items-center gap-1.5">
                                <span class="text-gray-400">Priority:</span>
                                <span class="capitalize {{ $task->priority === 'high' ? 'text-red-400' : ($task->priority === 'medium' ? 'text-amber-400' : 'text-blue-400') }}">
                                    {{ $task->priority ?? 'none' }}
                                </span>
                            </p>
                            <p class="flex items-center gap-1.5">
                                <span class="text-gray-400">Status:</span>
                                <span class="capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                            </p>
                            @if($task->assignees->isNotEmpty())
                            <p class="flex items-center gap-1.5">
                                <span class="text-gray-400">Assignees:</span>
                                <span class="truncate">{{ $task->assignees->pluck('name')->join(', ') }}</span>
                            </p>
                            @endif
                            <p class="flex items-center gap-1.5">
                                <span class="text-gray-400">Deadline:</span>
                                <span>{{ $task->deadline->format('d M Y') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($day['tasks']->count() > 3)
                <span class="block text-[10px] text-gray-400 dark:text-gray-500 px-1">
                    +{{ $day['tasks']->count() - 3 }} more
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick Create Task Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         wire:click.self="$set('showCreateModal', false)">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">New Task — {{ \Carbon\Carbon::parse($newTaskDeadline)->format('d M Y') }}</h2>
                <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input wire:model="newTaskTitle" type="text" placeholder="Task title..."
                       class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500" />
                @error('newTaskTitle')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Project --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Project</label>
                <select wire:model="newTaskProjectId"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <option value="">Select project...</option>
                    @foreach($userProjects as $proj)
                    <option value="{{ $proj['id'] }}">{{ $proj['name'] }}</option>
                    @endforeach
                </select>
                @error('newTaskProjectId')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Priority --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                <select wire:model="newTaskPriority"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            {{-- Deadline --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                <input wire:model="newTaskDeadline" type="date"
                       class="w-full text-sm rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500" />
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-2 pt-2">
                <button wire:click="$set('showCreateModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    Cancel
                </button>
                <button wire:click="createTask"
                        class="px-4 py-2 text-sm font-medium bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors">
                    Create Task
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
