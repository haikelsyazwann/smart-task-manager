<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Task Manager</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" style="background-image: radial-gradient(circle, #d1d5db 1px, transparent 1px); background-size: 20px 20px;">

<div class="min-h-screen flex flex-col">

    {{-- Nav --}}
    <nav class="h-14 bg-white border-b border-gray-200 flex items-center px-6 gap-4">
        <div class="flex items-center gap-2.5 flex-1">
            <div class="w-7 h-7 rounded-lg bg-violet-600 flex items-center justify-center text-white text-xs font-semibold">ST</div>
            <span class="text-sm font-semibold">Smart Tasks</span>
        </div>
        @if(Route::has('login'))
        <div class="flex items-center gap-3">
            @auth
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">Log in</a>
            @if(Route::has('register'))
            <a href="{{ route('register') }}" class="text-sm bg-violet-600 hover:bg-violet-700 text-white font-medium px-4 py-1.5 rounded-lg transition-colors">Get started</a>
            @endif
            @endauth
        </div>
        @endif
    </nav>

    {{-- Hero --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-20 text-center">
        <div class="inline-flex items-center gap-2 bg-violet-50 border border-violet-200 text-violet-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
            ✦ AI-Powered Productivity
        </div>
        <h1 class="text-4xl sm:text-5xl font-semibold text-gray-900 leading-tight max-w-2xl mb-4">
            Manage tasks smarter,<br>ship faster as a team
        </h1>
        <p class="text-gray-500 text-lg max-w-xl mb-8 leading-relaxed">
            Smart Task Manager combines Kanban boards, team collaboration, and AI assistance to keep your projects on track.
        </p>
        <div class="flex items-center gap-3">
            @auth
            <a href="{{ route('dashboard') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors text-sm">
                Go to Dashboard →
            </a>
            @else
            <a href="{{ route('register') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors text-sm">
                Get started free →
            </a>
            <a href="{{ route('login') }}" class="border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-2.5 rounded-lg transition-colors text-sm">
                Log in
            </a>
            @endauth
        </div>

        {{-- Feature cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-16 max-w-3xl w-full">
            <div class="bg-white border border-gray-200 rounded-xl p-5 text-left">
                <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 text-violet-600" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Kanban Board</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Drag tasks across To Do, In Progress, and Done columns in real time.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 text-left">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Team Collaboration</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Assign tasks, leave comments, and track activity across your whole team.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 text-left">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">AI Assistant</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Generate subtasks, improve descriptions, and summarize discussions with AI.</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 py-4 text-center text-xs text-gray-400">
        Smart Task Manager · Built with Laravel {{ Illuminate\Foundation\Application::VERSION }}
    </footer>

</div>
</body>
</html>
