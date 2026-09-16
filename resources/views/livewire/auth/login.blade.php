<div class="flex items-center justify-center min-h-screen bg-base-200">
    <div class="w-full max-w-md p-8 space-y-6 bg-base-100 rounded-xl shadow-lg">
        <div class="text-center">
            <h1 class="text-2xl font-bold">UniFi Rebooter</h1>
            <p class="text-sm text-gray-500">Silakan login dengan Username Anda</p>
        </div>

        <form wire:submit="authenticate" class="space-y-4">
            <div>
                <label class="label"><span class="label-text">Username</span></label>
                <input type="text" wire:model="username" class="input input-bordered w-full"
                    placeholder="Masukkan username" />
                @error('username')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="label"><span class="label-text">Password</span></label>
                <input type="password" wire:model="password" class="input input-bordered w-full"
                    placeholder="••••••••" />
                @error('password')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="cursor-pointer label gap-2">
                    <input type="checkbox" wire:model="remember" class="checkbox checkbox-sm" />
                    <span class="label-text text-sm">Ingat saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">Lupa
                    Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-full">Login</button>
        </form>
    </div>
</div>
