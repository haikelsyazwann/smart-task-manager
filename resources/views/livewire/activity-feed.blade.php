<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    @php
        $iconMap = [
            'task.created'        => ['bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600', '<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>'],
            'task.updated'        => ['bg-blue-50 dark:bg-blue-900/30 text-blue-600',    '<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>'],
            'task.deleted'        => ['bg-red-50 dark:bg-red-900/30 text-red-500',       '<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>'],
            'task.status_changed' => ['bg-amber-50 dark:bg-amber-900/30 text-amber-600', '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>'],
            'task.comment_added'  => ['bg-violet-50 dark:bg-violet-900/30 text-violet-600','<path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>'],
            'project.created'     => ['bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600','<path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>'],
            'team.created'        => ['bg-violet-50 dark:bg-violet-900/30 text-violet-600','<path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>'],
        ];
        $defaultIcon = ['bg-gray-100 dark:bg-gray-800 text-gray-500', '<path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>'];
    @endphp

    @forelse($logs as $log)
    @php [$iconClass, $iconPath] = $iconMap[$log->action] ?? $defaultIcon; @endphp
    <div class="flex items-start gap-3 px-4 py-3 border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
        <div class="w-6 h-6 rounded-full {{ $iconClass }} flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">{!! $iconPath !!}</svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug">
                <span class="font-semibold">{{ $log->user?->name ?? 'System' }}</span>
                <span class="text-gray-500 dark:text-gray-400"> {{ str_replace(['.','_'], [' ', ' '], $log->action) }}</span>
                @if($log->subject)
                <span class="text-gray-700 dark:text-gray-300 font-medium"> "{{ Str::limit($log->subject->title ?? $log->subject->name ?? '', 30) }}"</span>
                @endif
            </p>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
        </div>
        <p class="text-xs text-gray-400 dark:text-gray-500">No recent activity</p>
    </div>
    @endforelse

    <div class="px-4 py-2.5 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
        <a href="{{ route('activity.index') }}" class="text-xs text-violet-600 dark:text-violet-400 hover:underline font-medium">View all activity →</a>
    </div>
</div>
