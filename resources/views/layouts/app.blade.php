<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: {{ auth()->user()?->dark_mode ? 'true' : 'false' }},
          toggleDark() {
              this.darkMode = !this.darkMode;
              fetch('/profile/dark-mode', {
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Content-Type': 'application/json'
                  }
              });
          }
      }"
      :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Task Manager') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        if ({{ auth()->user()?->dark_mode ? 'true' : 'false' }}) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ── --}}
    <aside class="w-56 flex-shrink-0 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col overflow-y-auto">

        {{-- Logo --}}
        <div class="h-14 flex items-center gap-2.5 px-4 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
            <div class="w-7 h-7 rounded-lg bg-violet-600 flex items-center justify-center text-white text-xs font-semibold select-none">ST</div>
            <span class="text-sm font-semibold">Smart Tasks</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-2 space-y-0.5 overflow-y-auto">

            <p class="px-3 pt-3 pb-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Workspace</p>

            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h4a1 1 0 001-1v-3h2v3a1 1 0 001 1h4a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                </x-slot>
                Dashboard
            </x-nav-link>

            <x-nav-link href="{{ route('projects.index') }}" :active="request()->routeIs('projects*')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                </x-slot>
                Projects
            </x-nav-link>

            <x-nav-link href="{{ route('my-tasks') }}" :active="request()->routeIs('my-tasks')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                </x-slot>
                My Tasks
            </x-nav-link>

            <x-nav-link href="{{ route('activity.index') }}" :active="request()->routeIs('activity*')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                </x-slot>
                Activity
            </x-nav-link>

            <x-nav-link href="{{ route('calendar') }}" :active="request()->routeIs('calendar')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                </x-slot>
                Calendar
            </x-nav-link>

            @role('admin|manager')
            <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Management</p>

            <x-nav-link href="{{ route('teams.index') }}" :active="request()->routeIs('teams*')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                </x-slot>
                Teams
            </x-nav-link>
            @endrole

            @role('admin')
            <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users*')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </x-slot>
                Users
            </x-nav-link>

            <x-nav-link href="{{ route('admin.roles') }}" :active="request()->routeIs('admin.roles*')">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </x-slot>
                Roles & Access
            </x-nav-link>
            @endrole

            {{-- Projects quick links --}}
            @php
                $myProjects = auth()->user()->hasRole('admin')
                    ? \App\Models\Project::latest()->take(6)->get()
                    : auth()->user()->teams()->with('projects')->get()->pluck('projects')->flatten()->unique('id')->take(6);
            @endphp
            @if($myProjects->isNotEmpty())
            <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Projects</p>
            @foreach($myProjects as $sp)
            <a href="{{ route('projects.board', $sp) }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition-colors {{ request()->is('projects/'.$sp->id.'/board') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $sp->color ?? '#8b5cf6' }}"></span>
                <span class="truncate text-sm">{{ $sp->name }}</span>
            </a>
            @endforeach
            @endif

        </nav>

        {{-- User footer --}}
        <div class="border-t border-gray-200 dark:border-gray-800 p-3">
            <div class="flex items-center gap-2">
                <x-avatar :avatar="auth()->user()->avatar ?? 'boy1'" size="sm" />
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-400 capitalize">{{ auth()->user()->getRoleNames()->implode(', ') }}</p>
                </div>
                <a href="{{ route('profile') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-sm">⚙</a>
            </div>
        </div>
    </aside>

    {{-- ── MAIN ── --}}
    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- Topbar --}}
        <header class="h-14 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center px-5 gap-3 flex-shrink-0 z-10">
            <div class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                @isset($header){{ $header }}@endisset
            </div>

            {{-- Dark mode toggle --}}
            <button @click="toggleDark()"
                    class="p-1.5 rounded-md text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-base leading-none">
                <span x-text="darkMode ? '☀️' : '🌙'"></span>
            </button>

            {{-- Profile dropdown --}}
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="rounded-full focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-1 transition-all">
                        <x-avatar :avatar="auth()->user()->avatar ?? 'boy1'" size="md" />
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="px-4 py-2.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                        <x-avatar :avatar="auth()->user()->avatar ?? 'boy1'" size="md" />
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <x-dropdown-link href="{{ route('profile') }}">Profile & Settings</x-dropdown-link>
                    <div class="border-t border-gray-100 dark:border-gray-700 mt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                Log Out
                            </button>
                        </form>
                    </div>
                </x-slot>
            </x-dropdown>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-auto">

            {{-- Toast: success --}}
            @if(session('success'))
            <div x-data="{show:true}" x-show="show"
                 x-init="setTimeout(()=>show=false,3500)"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-4 right-4 z-50 bg-emerald-600 text-white text-sm px-4 py-2.5 rounded-lg shadow-lg flex items-center gap-2 pointer-events-none">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Toast: error --}}
            @if(session('error'))
            <div x-data="{show:true}" x-show="show"
                 x-init="setTimeout(()=>show=false,4000)"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-4 right-4 z-50 bg-red-600 text-white text-sm px-4 py-2.5 rounded-lg shadow-lg flex items-center gap-2 pointer-events-none">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>
@livewireScripts
@stack('scripts')
</body>
</html>
