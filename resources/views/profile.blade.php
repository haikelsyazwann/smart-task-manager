<x-app-layout>
    <x-slot name="header">
        <span class="font-medium">Profile & Settings</span>
    </x-slot>

    <div class="p-6 max-w-2xl space-y-6">

        {{-- Profile Info --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Profile Information</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update your account's profile information and email address.</p>
            </div>
            <div class="p-5">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        {{-- Password --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Update Password</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-5">
                <livewire:profile.update-password-form />
            </div>
        </div>

        {{-- Dark mode preference --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Appearance</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Choose your preferred color theme.</p>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Dark mode</p>
                        <p class="text-xs text-gray-400 mt-0.5">Switch between light and dark interface.</p>
                    </div>
                    <button @click="toggleDark()"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"
                            :class="darkMode ? 'bg-violet-600' : 'bg-gray-200 dark:bg-gray-700'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                            :class="darkMode ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Delete account --}}
        <div class="bg-white dark:bg-gray-900 border border-red-200 dark:border-red-900/50 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-red-200 dark:border-red-900/50">
                <h2 class="text-sm font-semibold text-red-700 dark:text-red-400">Delete Account</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="p-5">
                <livewire:profile.delete-user-form />
            </div>
        </div>

    </div>
</x-app-layout>
