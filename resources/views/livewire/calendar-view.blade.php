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
        <div class="border-r border-b border-gray-200 dark:border-gray-800 p-1 min-h-[110px]
            {{ !$day['isCurrentMonth'] ? 'bg-gray-50 dark:bg-gray-900/50' : 'bg-white dark:bg-gray-900' }}">

            {{-- Date number --}}
            <div class="flex items-center justify-center mb-1">
                <span class="w-6 h-6 flex items-center justify-center text-xs font-medium rounded-full
                    {{ $day['isToday'] ? 'bg-violet-600 text-white' : ($day['isCurrentMonth'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-300 dark:text-gray-600') }}">
                    {{ $day['date']->day }}
                </span>
            </div>

            {{-- Tasks --}}
            <div class="space-y-0.5">
                @foreach($day['tasks']->take(3) as $task)
                <a href="{{ route('projects.board', $task->project) }}"
                   title="{{ $task->title }}"
                   class="block truncate text-[11px] px-1.5 py-0.5 rounded font-medium leading-tight
                       {{ $task->status === 'done'
                           ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 line-through'
                           : ($task->priority === 'high'
                               ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'
                               : ($task->priority === 'medium'
                                   ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'
                                   : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300')) }}">
                    {{ $task->title }}
                </a>
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

</div>
