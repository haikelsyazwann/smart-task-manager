<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Task Manager') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" style="background-image: radial-gradient(circle, #d1d5db 1px, transparent 1px); background-size: 20px 20px;">

<div class="min-h-screen flex">

    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-violet-600 flex-col justify-between p-10">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center text-white text-xs font-semibold">ST</div>
            <span class="text-white text-sm font-semibold">Smart Tasks</span>
        </div>
        <div>
            <h2 class="text-white text-3xl font-semibold leading-snug mb-4">
                Your team's productivity,<br>supercharged with AI
            </h2>
            <div class="space-y-3">
                @foreach(['Kanban boards with real-time updates', 'AI-generated subtasks and descriptions', 'Role-based access for your whole team', 'Activity logs and deadline tracking'] as $feature)
                <div class="flex items-center gap-2.5 text-violet-100 text-sm">
                    <svg class="w-4 h-4 text-violet-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ $feature }}
                </div>
                @endforeach
            </div>
        </div>
        <p class="text-violet-300 text-xs">Smart Task Manager · AI-Powered Team Productivity</p>
    </div>

    {{-- Right panel --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            {{-- Mobile logo --}}
            <div class="flex items-center gap-2.5 mb-8 lg:hidden">
                <div class="w-7 h-7 rounded-lg bg-violet-600 flex items-center justify-center text-white text-xs font-semibold">ST</div>
                <span class="text-sm font-semibold">Smart Tasks</span>
            </div>
            {{ $slot }}
        </div>
    </div>

</div>
</body>
</html>
