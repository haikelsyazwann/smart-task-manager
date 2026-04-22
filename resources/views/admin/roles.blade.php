<x-app-layout>
    <x-slot name="header">
        <span class="text-gray-400">Admin</span>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="font-medium">Roles & Access</span>
    </x-slot>

    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-lg font-semibold">Roles & Access Control</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Spatie Permission roles control what each user can do.</p>
        </div>

        @php
        $matrix = [
            ['category' => 'Dashboard',
             'rows' => [
                ['action' => 'View dashboard & stats',       'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'See all projects in stats',    'admin' => true, 'manager' => false, 'member' => false],
             ]
            ],
            ['category' => 'Projects',
             'rows' => [
                ['action' => 'View own team projects',       'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Create new projects',          'admin' => true, 'manager' => true,  'member' => false],
                ['action' => 'Edit / delete any project',    'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Edit / delete own project',    'admin' => true, 'manager' => true,  'member' => false],
             ]
            ],
            ['category' => 'Tasks',
             'rows' => [
                ['action' => 'View tasks in team projects',  'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Create tasks',                 'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Edit assigned tasks',          'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Move tasks between columns',   'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Delete any task',              'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Delete own tasks',             'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Assign users to tasks',        'admin' => true, 'manager' => true,  'member' => false],
             ]
            ],
            ['category' => 'Comments & Files',
             'rows' => [
                ['action' => 'Post comments',                'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Delete own comments',          'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'Delete any comment',           'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Upload attachments',           'admin' => true, 'manager' => true,  'member' => true],
             ]
            ],
            ['category' => 'Teams',
             'rows' => [
                ['action' => 'Create teams',                 'admin' => true, 'manager' => true,  'member' => false],
                ['action' => 'Edit / delete own team',       'admin' => true, 'manager' => true,  'member' => false],
                ['action' => 'Edit / delete any team',       'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Add / remove team members',    'admin' => true, 'manager' => true,  'member' => false],
             ]
            ],
            ['category' => 'User Management (Admin only)',
             'rows' => [
                ['action' => 'View all users',               'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Create / invite users',        'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Edit user roles',              'admin' => true, 'manager' => false, 'member' => false],
                ['action' => 'Remove users',                 'admin' => true, 'manager' => false, 'member' => false],
             ]
            ],
            ['category' => 'AI Features',
             'rows' => [
                ['action' => 'AI improve description',       'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'AI generate subtasks',         'admin' => true, 'manager' => true,  'member' => true],
                ['action' => 'AI summarize comments',        'admin' => true, 'manager' => true,  'member' => true],
             ]
            ],
        ];
        @endphp

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 w-1/2">Permission</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-violet-700 dark:text-violet-400 w-1/6">Admin</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-blue-700 dark:text-blue-400 w-1/6">Manager</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 w-1/6">Member</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrix as $section)
                    <tr class="border-t border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
                        <td colspan="4" class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            {{ $section['category'] }}
                        </td>
                    </tr>
                    @foreach($section['rows'] as $row)
                    <tr class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-5 py-2.5 text-gray-700 dark:text-gray-300">{{ $row['action'] }}</td>
                        @foreach(['admin','manager','member'] as $r)
                        <td class="px-4 py-2.5 text-center">
                            @if($row[$r])
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold">✓</span>
                            @else
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-300 dark:text-gray-600 text-xs">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Role legend --}}
        <div class="grid grid-cols-3 gap-4">
            @foreach([
                ['name'=>'Admin','color'=>'violet','desc'=>'Full system access. Can manage users, teams, projects, and all tasks. Sees all data across the workspace.'],
                ['name'=>'Manager','color'=>'blue','desc'=>'Team leader. Can create projects, manage their own teams, and assign tasks to members. Cannot manage users.'],
                ['name'=>'Member','color'=>'gray','desc'=>'Regular contributor. Can create and update tasks in their team projects, add comments, and upload files.'],
            ] as $r)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-sm font-semibold px-2.5 py-0.5 rounded-full
                        {{ $r['color'] === 'violet' ? 'bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-400'
                         : ($r['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400'
                         : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400') }}">
                        {{ $r['name'] }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $r['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
