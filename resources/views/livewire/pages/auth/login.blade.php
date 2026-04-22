<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Welcome back</h1>
        <p class="text-sm text-gray-500 mt-1">Sign in to your account to continue</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="block text-xs font-medium text-gray-600 mb-1">Email address</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                   placeholder="you@company.com">
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-medium text-gray-600">Password</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate class="text-xs text-violet-600 hover:text-violet-700 hover:underline">Forgot password?</a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <div class="flex items-center gap-2">
            <input wire:model="form.remember" id="remember" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 accent-violet-600 cursor-pointer">
            <label for="remember" class="text-xs text-gray-600 cursor-pointer">Remember me</label>
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white font-medium text-sm py-2.5 rounded-lg transition-colors">
            <span wire:loading.remove>Sign in →</span>
            <span wire:loading>Signing in…</span>
        </button>
    </form>

    @if(Route::has('register'))
    <p class="text-center text-xs text-gray-500 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate class="text-violet-600 hover:underline font-medium">Create one</a>
    </p>
    @endif
</div>
