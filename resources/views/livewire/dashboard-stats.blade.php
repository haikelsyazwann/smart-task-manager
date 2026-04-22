<div class="space-y-4">
    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Tasks</p>
                <span class="w-7 h-7 rounded-lg bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalTasks }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5">across all projects</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Completed</p>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-semibold text-emerald-600 dark:text-emerald-400">{{ $completedTasks }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5">{{ $totalTasks > 0 ? round($completedTasks / $totalTasks * 100) : 0 }}% completion rate</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">In Progress</p>
                <span class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-semibold text-amber-500 dark:text-amber-400">{{ $inProgressTasks }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5">active right now</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Overdue</p>
                <span class="w-7 h-7 rounded-lg {{ $overdueTasks > 0 ? 'bg-red-50 dark:bg-red-900/30' : 'bg-gray-50 dark:bg-gray-800' }} flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 {{ $overdueTasks > 0 ? 'text-red-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </span>
            </div>
            <div>
                <p class="text-2xl font-semibold {{ $overdueTasks > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">{{ $overdueTasks }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5">past deadline</p>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    @if($totalTasks > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4"
         x-data
         x-init="
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
            s.onload = function() {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                const textColor = isDark ? '#9ca3af' : '#6b7280';

                const donutCtx = document.getElementById('donut-chart');
                if (donutCtx) {
                    new Chart(donutCtx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [{{ $completedTasks }}, {{ $inProgressTasks }}, {{ $todoTasks }}],
                                backgroundColor: ['#10b981', '#f59e0b', '#60a5fa'],
                                borderWidth: 0,
                                hoverOffset: 4,
                            }]
                        },
                        options: {
                            cutout: '72%',
                            plugins: { legend: { display: false }, tooltip: { enabled: true } },
                            animation: { duration: 600 },
                        }
                    });
                }

                const barCtx = document.getElementById('bar-chart');
                if (barCtx) {
                    new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: {{ Js::from($chartDays) }},
                            datasets: [{
                                data: {{ Js::from($chartCounts) }},
                                backgroundColor: '#8b5cf6',
                                borderRadius: 4,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip: { enabled: true } },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: textColor, font: { size: 10 } },
                                    border: { display: false },
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: textColor, font: { size: 10 }, stepSize: 1, precision: 0 },
                                    grid: { color: gridColor },
                                    border: { display: false },
                                }
                            },
                            animation: { duration: 600 },
                        }
                    });
                }
            };
            document.head.appendChild(s);
         ">

        {{-- Donut chart - task status --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-3">Task Status</p>
            <div class="flex items-center gap-4">
                <div class="relative w-24 h-24 flex-shrink-0">
                    <canvas id="donut-chart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $totalTasks }}</span>
                    </div>
                </div>
                <div class="space-y-2 flex-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Done</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $completedTasks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-400">In Progress</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $inProgressTasks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-400 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-400">To Do</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $todoTasks }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bar chart - completions last 7 days --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-3">Tasks Completed — Last 7 Days</p>
            <div class="h-24">
                <canvas id="bar-chart"></canvas>
            </div>
        </div>

    </div>
    @endif
</div>
