<x-app-layout>
    <x-slot name="header">
        <span class="text-gray-400">Overview</span>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="font-medium">Dashboard</span>
        @role('admin')
        <span class="ml-3 text-[10px] bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 px-2 py-0.5 rounded-full font-semibold uppercase tracking-wide">Admin</span>
        @endrole
    </x-slot>

    <div class="p-6 space-y-6">
        <livewire:dashboard-stats />

        @role('admin|manager')
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                + New Project
            </a>
            <a href="{{ route('teams.index') }}" class="inline-flex items-center gap-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Manage Teams
            </a>
            @role('admin')
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Manage Users
            </a>
            @endrole
        </div>
        @endrole

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">My Projects</h2>
                    <a href="{{ route('projects.index') }}" class="text-xs text-violet-600 dark:text-violet-400 hover:underline">View all</a>
                </div>
                <livewire:project-list />
            </div>
            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Recent Activity</h2>
                <livewire:activity-feed />
            </div>
        </div>
    </div>
</x-app-layout>
