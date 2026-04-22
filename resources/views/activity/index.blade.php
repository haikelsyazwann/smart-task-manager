<x-app-layout>
    <x-slot name="header">
        <span class="text-gray-400">Workspace</span>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="font-medium">Activity Log</span>
    </x-slot>

    <div class="p-6 max-w-3xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="text-lg font-semibold">Activity Log</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">All actions across the workspace</p>
            </div>
        </div>

        @php
            $actionIcons = [
                'task.created'       => ['icon' => '＋', 'color' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'],
                'task.updated'       => ['icon' => '✎',  'color' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400'],
                'task.deleted'       => ['icon' => '🗑',  'color' => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400'],
                'task.status_changed'=> ['icon' => '→',  'color' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400'],
                'task.comment_added' => ['icon' => '💬', 'color' => 'bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-400'],
                'project.created'    => ['icon' => '❏',  'color' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'],
                'project.updated'    => ['icon' => '✎',  'color' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400'],
                'team.created'       => ['icon' => '👥', 'color' => 'bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-400'],
            ];
            $actionLabels = [
                'task.created'        => 'created task',
                'task.updated'        => 'updated task',
                'task.deleted'        => 'deleted a task',
                'task.status_changed' => 'moved task',
                'task.comment_added'  => 'commented on',
                'project.created'     => 'created project',
                'project.updated'     => 'updated project',
                'team.created'        => 'created team',
            ];
        @endphp

        <div class="space-y-0 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
            @forelse($logs as $log)
            @php
                $ai = $actionIcons[$log->action] ?? ['icon' => '⟳', 'color' => 'bg-gray-100 dark:bg-gray-800 text-gray-500'];
                $label = $actionLabels[$log->action] ?? str_replace(['.','_'], [' ',' '], $log->action);
                $subjectName = optional($log->subject)->title
                    ?? optional($log->subject)->name
                    ?? $log->properties['subject_name']
                    ?? 'an item';
            @endphp
            <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                <div class="w-7 h-7 rounded-full {{ $ai['color'] }} flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                    {{ $ai['icon'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $log->user?->name ?? 'System' }}</span>
                        <span class="text-gray-500 dark:text-gray-400"> {{ $label }} </span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $subjectName }}</span>
                        @if(!empty($log->properties['status']))
                        <span class="text-gray-400"> → <span class="font-medium text-gray-700 dark:text-gray-300">{{ ucwords(str_replace('_',' ',$log->properties['status'])) }}</span></span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }} · {{ $log->created_at->format('M j, Y H:i') }}</p>
                </div>
            </div>
            @empty
            <div class="px-5 py-16 text-center text-gray-400 dark:text-gray-500">
                <p class="text-3xl mb-2">⟳</p>
                <p class="font-medium text-gray-600 dark:text-gray-300">No activity yet</p>
            </div>
            @endforelse
        </div>

        @if($logs->hasPages())
        <div class="mt-4">{{ $logs->links() }}</div>
        @endif
    </div>
</x-app-layout>
