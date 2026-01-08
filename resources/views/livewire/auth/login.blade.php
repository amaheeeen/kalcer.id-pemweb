<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function toJSON() { return; }

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();

        // Redirect Cerdas
        if (Auth::user()->role === 'business_owner') {
            $this->redirect(route('business.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }
}; ?>

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to Kalcer.id')" :description="__('Enter your email and password below')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="login" class="flex flex-col gap-6">
            
            <flux:input wire:model="email" label="Email" type="email" required autofocus placeholder="email@example.com" />

            <div class="relative">
                <flux:input wire:model="password" label="Password" type="password" required viewable placeholder="Password" />
            </div>

            <flux:checkbox wire:model="remember" label="Remember me" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        <div class="text-center text-sm text-zinc-600">
            Don't have an account? <flux:link :href="route('register')" wire:navigate>Sign up</flux:link>
        </div>
    </div>
</x-layouts.auth>