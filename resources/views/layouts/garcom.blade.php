<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Restaurante') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"DM Sans"', 'sans-serif'] },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('layouts.partials.restaurant-theme')
    
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f8fafc; }
        .bg-garcom-sidebar { background-color: var(--color-secondary); }
        .text-garcom-sidebar { color: #ffffff; }
        .hover-garcom-sidebar:hover, .active-garcom-sidebar {
            background-color: var(--color-secondary-dark);
            color: #ffffff;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">

    @php
        $restaurantConfig = \App\Models\RestaurantConfig::storefront();
    @endphp

    <!-- Mobile sidebar -->
    <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

        <div class="fixed inset-0 flex">
            <div x-show="sidebarOpen" x-transition.translate class="relative mr-16 flex w-full max-w-[280px] flex-1">
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                    <button @click="sidebarOpen = false" type="button" class="-m-2.5 p-2.5 text-white">
                        <span class="sr-only">Close sidebar</span>
                        <i class="fas fa-times text-3xl drop-shadow-md"></i>
                    </button>
                </div>

                <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4 bg-garcom-sidebar shadow-2xl">
                    <div class="flex h-24 shrink-0 items-center justify-center mt-4 border-b border-white/10 pb-4">
                        @if($restaurantConfig->logo)
                            <img src="{{ Storage::url($restaurantConfig->logo) }}" alt="Logo" class="max-h-16 w-auto object-contain brightness-0 invert drop-shadow-md">
                        @else
                            <span class="text-2xl font-black tracking-tight text-white drop-shadow-md">{{ config('app.name') }}</span>
                        @endif
                    </div>
                    
                    <nav class="flex flex-1 flex-col mt-2">
                        <ul role="list" class="flex flex-1 flex-col gap-y-3">
                            <li>
                                <a href="{{ route('garcom.index') }}" class="group flex gap-x-4 rounded-xl p-4 text-[15px] leading-6 font-bold text-garcom-sidebar {{ request()->routeIs('garcom.index') ? 'active-garcom-sidebar' : 'hover-garcom-sidebar' }} shadow-sm">
                                    <i class="fas fa-th-large text-xl w-6 text-center pt-0.5 opacity-90 group-hover:opacity-100"></i>
                                    Salão e Mesas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('garcom.pedidos') }}" class="group flex gap-x-4 rounded-xl p-4 text-[15px] leading-6 font-bold text-garcom-sidebar {{ request()->routeIs('garcom.pedidos') ? 'active-garcom-sidebar' : 'hover-garcom-sidebar' }} shadow-sm">
                                    <i class="fas fa-clipboard-list text-xl w-6 text-center pt-0.5 opacity-90 group-hover:opacity-100"></i>
                                    Pedidos Pendentes
                                </a>
                            </li>
                        </ul>

                        <div class="mt-auto border-t border-white/10 pt-6 pb-4">
                            <div class="flex items-center gap-x-4 mb-6">
                                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-black text-lg shadow-inner">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-base font-bold text-white truncate">{{ auth()->user()->name }}</span>
                                    <span class="block text-xs font-semibold text-white/70 uppercase tracking-wider">Garçom</span>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="group flex w-full gap-x-4 rounded-xl p-4 text-[15px] leading-6 font-bold text-white/80 hover:text-white hover-garcom-sidebar transition-colors shadow-sm bg-white/5 border border-white/10">
                                    <i class="fas fa-sign-out-alt text-xl w-6 text-center pt-0.5"></i>
                                    Sair do Sistema
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4 bg-garcom-sidebar shadow-xl">
            <div class="flex h-24 shrink-0 items-center justify-center mt-4 border-b border-white/10 pb-4">
                @if($restaurantConfig->logo)
                    <img src="{{ Storage::url($restaurantConfig->logo) }}" alt="Logo" class="max-h-16 w-auto object-contain brightness-0 invert drop-shadow-md">
                @else
                    <span class="text-2xl font-black tracking-tight text-white drop-shadow-md">{{ config('app.name') }}</span>
                @endif
            </div>
            
            <nav class="flex flex-1 flex-col mt-4">
                <ul role="list" class="flex flex-1 flex-col gap-y-3">
                    <li>
                        <a href="{{ route('garcom.index') }}" class="group flex gap-x-4 rounded-xl p-4 text-[15px] leading-6 font-bold text-garcom-sidebar {{ request()->routeIs('garcom.index') ? 'active-garcom-sidebar' : 'hover-garcom-sidebar' }} shadow-sm">
                            <i class="fas fa-th-large text-xl w-6 text-center pt-0.5"></i>
                            Salão e Mesas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('garcom.pedidos') }}" class="group flex gap-x-4 rounded-xl p-4 text-[15px] leading-6 font-bold text-garcom-sidebar {{ request()->routeIs('garcom.pedidos') ? 'active-garcom-sidebar' : 'hover-garcom-sidebar' }} shadow-sm">
                            <i class="fas fa-clipboard-list text-xl w-6 text-center pt-0.5"></i>
                            Pedidos Pendentes
                        </a>
                    </li>
                </ul>

                <div class="mt-auto border-t border-white/10 pt-6 pb-4">
                    <div class="flex items-center gap-x-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-black text-lg shadow-inner">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-base font-bold text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="block text-xs font-semibold text-white/70 uppercase tracking-wider">Garçom</span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="group flex w-full gap-x-4 rounded-xl p-4 text-[15px] leading-6 font-bold text-white/80 hover:text-white hover-garcom-sidebar transition-colors shadow-sm bg-white/5 border border-white/10">
                            <i class="fas fa-sign-out-alt text-xl w-6 text-center pt-0.5"></i>
                            Sair do Sistema
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>

    <div class="lg:pl-72 flex min-h-screen flex-col">
        <!-- Topbar for Mobile -->
        <div class="sticky top-0 z-40 flex h-20 shrink-0 items-center justify-between border-b bg-custom-primary px-5 shadow-sm lg:hidden border-custom-secondary">
            <button @click="sidebarOpen = true" type="button" class="-ml-2 p-3 text-custom-secondary flex items-center justify-center bg-custom-secondary-soft rounded-xl hover:bg-slate-100 transition-colors">
                <span class="sr-only">Open sidebar</span>
                <i class="fas fa-bars text-2xl"></i>
            </button>
            <div class="font-extrabold text-custom-main flex-1 text-center text-xl tracking-tight">@yield('title')</div>
            <div class="w-12 flex justify-end">
                @stack('topbar-action')
            </div>
        </div>

        <main class="flex-1">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
