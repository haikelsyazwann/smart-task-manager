<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        event(new Registered($user = User::create($validated)));
        Auth::login($user);
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Create your account</h1>
        <p class="text-sm text-gray-500 mt-1">Start managing tasks with your team today</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label for="name" class="block text-xs font-medium text-gray-600 mb-1">Full name</label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                   placeholder="Jane Smith">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <label for="email" class="block text-xs font-medium text-gray-600 mb-1">Email address</label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                   placeholder="you@company.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <label for="password" class="block text-xs font-medium text-gray-600 mb-1">Password</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                   placeholder="Min 8 characters">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-gray-600 mb-1">Confirm password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white font-medium text-sm py-2.5 rounded-lg transition-colors">
            <span wire:loading.remove>Create account →</span>
            <span wire:loading>Creating account…</span>
        </button>
    </form>

    <p class="text-center text-xs text-gray-500 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-violet-600 hover:underline font-medium">Sign in</a>
    </p>
</div>
