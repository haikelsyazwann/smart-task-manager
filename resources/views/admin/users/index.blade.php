<x-app-layout>
    <x-slot name="header">
        <span class="text-gray-400">Admin</span>
        <span class="text-gray-300 dark:text-gray-600">/</span>
        <span class="font-medium">User Management</span>
    </x-slot>

    <div class="p-6 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold">Users</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $users->total() }} registered user{{ $users->total() !== 1 ? 's' : '' }}</p>
            </div>
            <button onclick="document.getElementById('create-user-modal').showModal()"
                    class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                + Add User
            </button>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">User</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Email</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Role</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Teams</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Tasks</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Joined</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($users as $user)
                    @php
                        $role = $user->getRoleNames()->first() ?? 'member';
                        $roleStyle = match($role) {
                            'admin'   => 'bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-400',
                            'manager' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400',
                            default   => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900 flex items-center justify-center text-[10px] font-semibold text-violet-700 dark:text-violet-300">
                                    {{ strtoupper(substr($user->name,0,2)) }}
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                <span class="text-[9px] bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-1.5 py-0.5 rounded">you</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize {{ $roleStyle }}">{{ $role }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                            {{ $user->teams->count() }} team{{ $user->teams->count() !== 1 ? 's' : '' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                            {{ $user->assignedTasks->count() }} task{{ $user->assignedTasks->count() !== 1 ? 's' : '' }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEditUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $role }}')"
                                        class="text-xs text-violet-600 dark:text-violet-400 hover:underline font-medium">Edit</button>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Remove {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Remove</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $users->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Create User Modal --}}
    <dialog id="create-user-modal" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-xl p-0 w-full max-w-md backdrop:bg-black/40">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="font-semibold text-sm text-gray-900 dark:text-gray-100">Add New User</h2>
            <button onclick="document.getElementById('create-user-modal').close()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Full name *</label>
                    <input type="text" name="name" required placeholder="Jane Smith"
                           class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Email *</label>
                    <input type="email" name="email" required placeholder="jane@company.com"
                           class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Password *</label>
                    <input type="password" name="password" required placeholder="Min 8 chars"
                           class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Role *</label>
                    <select name="role" required class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                        @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ $r->name === 'member' ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="document.getElementById('create-user-modal').close()"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-1.5 text-sm bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors font-medium">Create User</button>
            </div>
        </form>
    </dialog>

    {{-- Edit User Modal --}}
    <dialog id="edit-user-modal" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-xl p-0 w-full max-w-md backdrop:bg-black/40">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="font-semibold text-sm text-gray-900 dark:text-gray-100">Edit User</h2>
            <button onclick="document.getElementById('edit-user-modal').close()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">✕</button>
        </div>
        <form method="POST" id="edit-user-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Full name *</label>
                <input type="text" name="name" id="eu-name" required
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Email *</label>
                <input type="email" name="email" id="eu-email" required
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">New password <span class="text-gray-400">(leave blank to keep current)</span></label>
                <input type="password" name="password" placeholder="Min 8 chars"
                       class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Role *</label>
                <select name="role" id="eu-role" required class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="document.getElementById('edit-user-modal').close()"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-1.5 text-sm bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors font-medium">Save Changes</button>
            </div>
        </form>
    </dialog>

    <script>
    function openEditUser(id, name, email, role) {
        document.getElementById('eu-name').value  = name;
        document.getElementById('eu-email').value = email;
        document.getElementById('eu-role').value  = role;
        document.getElementById('edit-user-form').action = '/admin/users/' + id;
        document.getElementById('edit-user-modal').showModal();
    }
    </script>
</x-app-layout>
