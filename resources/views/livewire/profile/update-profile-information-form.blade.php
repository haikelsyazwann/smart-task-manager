<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $avatar = 'boy1';

    public function mount(): void
    {
        $this->name   = Auth::user()->name;
        $this->email  = Auth::user()->email;
        $this->avatar = Auth::user()->avatar ?? 'boy1';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['required', 'string', 'in:boy1,boy2,boy3,boy4,boy5,girl1,girl2,girl3,girl4,girl5'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }
        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="space-y-5">

    {{-- Avatar picker --}}
    <div>
        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-3">Choose your avatar</p>

        <div class="space-y-3">
            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Boy</p>
            <div class="flex gap-3 flex-wrap">
                @foreach(['boy1','boy2','boy3','boy4','boy5'] as $av)
                <button type="button" wire:click="$set('avatar','{{ $av }}')"
                        class="rounded-full transition-all duration-150 {{ $avatar === $av ? 'ring-2 ring-violet-500 ring-offset-2 dark:ring-offset-gray-900 scale-110' : 'opacity-60 hover:opacity-100 hover:scale-105' }}">
                    <x-avatar :avatar="$av" size="lg" />
                </button>
                @endforeach
            </div>

            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Girl</p>
            <div class="flex gap-3 flex-wrap">
                @foreach(['girl1','girl2','girl3','girl4','girl5'] as $av)
                <button type="button" wire:click="$set('avatar','{{ $av }}')"
                        class="rounded-full transition-all duration-150 {{ $avatar === $av ? 'ring-2 ring-violet-500 ring-offset-2 dark:ring-offset-gray-900 scale-110' : 'opacity-60 hover:opacity-100 hover:scale-105' }}">
                    <x-avatar :avatar="$av" size="lg" />
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="border-t border-gray-100 dark:border-gray-800 pt-5">
        <form wire:submit="updateProfileInformation" class="space-y-4">
            <div>
                <label for="name" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Full name</label>
                <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Email address</label>
                <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}
                        <button wire:click.prevent="sendVerification" class="underline text-sm text-violet-600 hover:text-violet-700">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-emerald-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                    @endif
                </div>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-1">
                <x-primary-button>Save changes</x-primary-button>
                <x-action-message class="text-sm text-emerald-600" on="profile-updated">
                    ✓ Saved
                </x-action-message>
            </div>
        </form>
    </div>
</section>
