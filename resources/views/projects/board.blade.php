<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">Projects</a>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="w-2 h-2 rounded-full inline-block" style="background:{{ $project->color }}"></span>
        <span class="font-medium">{{ $project->name }}</span>
        <span class="text-xs text-gray-400 ml-2">{{ $project->team->name }}</span>
    </x-slot>

    <livewire:task-board :project="$project" />
</x-app-layout>
