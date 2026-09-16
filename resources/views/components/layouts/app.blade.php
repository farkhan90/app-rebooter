<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'UniFi Manager' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50">

    {{-- NAVBAR KHUSUS MOBILE --}}
    <x-nav sticky class="lg:hidden bg-base-100">
        <x-slot:brand>
            <div class="font-bold text-lg text-primary">UniFi Rebooter</div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="btn btn-ghost drawer-button lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block w-5 h-5 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- LAYOUT UTAMA MARY-UI --}}
    <x-main with-nav full-width>
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100">
            <div class="p-6 font-bold text-xl hidden lg:block tracking-wider text-primary">
                ⚡ UNIFI CTRL
            </div>

            <x-menu activate-by-route>
                <x-menu-item title="Dashboard" icon="o-home" link="/dashboard" />

                {{-- Separator menggunakan divider DaisyUI --}}
                <hr class="my-2 border-base-200" />

                <x-menu-item title="Logout" icon="o-power" link="/logout" />
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>
</body>

</html>
