<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Restaurante SaaS') }}</title>

    <!-- Tailwind CSS (via CDN para exemplo, no prod use Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js (apenas para o dashboard) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-900" x-data="{ sidebarOpen: false }">

    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
    <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/80"></div>

        <div class="fixed inset-0 flex">
            <div x-show="sidebarOpen" x-transition.translate class="relative mr-16 flex w-full max-w-xs flex-1">
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                    <button @click="sidebarOpen = false" type="button" class="-m-2.5 p-2.5">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar component for mobile -->
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4 ring-1 ring-white/10">
                    <div class="flex h-16 shrink-0 items-center text-white font-bold text-xl tracking-tight">
                        {{ config('app.name') }} Admin
                    </div>
                    @include('layouts.admin-nav')
                </div>
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4">
            <div class="flex h-16 shrink-0 items-center justify-between">
                <span class="text-white font-bold text-xl tracking-tight">{{ config('app.name') }} Admin</span>
                @if(isset($pedidosAtivos) && $pedidosAtivos > 0)
                    <span class="inline-flex items-center rounded-full bg-brand-500/10 px-2 py-1 text-xs font-medium text-brand-500 ring-1 ring-inset ring-brand-500/20">
                        {{ $pedidosAtivos }} online
                    </span>
                @endif
            </div>
            @include('layouts.admin-nav')
        </div>
    </div>

    <div class="lg:pl-72 flex flex-col min-h-screen">
        <!-- Top HTML Nav -->
        <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <button @click="sidebarOpen = true" type="button" class="-m-2.5 p-2.5 text-slate-700 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <!-- Separator -->
            <div class="h-6 w-px bg-slate-200 lg:hidden" aria-hidden="true"></div>

            <div class="flex flex-1 justify-end gap-x-4 self-stretch lg:gap-x-6">
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <!-- Config Btn -->
                    <a href="{{ route('admin.configuracoes.index') }}" class="-m-2.5 p-2.5 text-slate-400 hover:text-slate-500">
                        <span class="sr-only">Configurações</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>

                    <!-- Separator -->
                    <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200" aria-hidden="true"></div>

                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" type="button" class="-m-1.5 flex items-center p-1.5" id="user-menu-button">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full bg-slate-50" src="{{ auth()->user()->avatar_url ?? auth()->user()->avatar }}" alt="">
                            <span class="hidden lg:flex lg:items-center">
                                <span class="ml-4 text-sm font-semibold leading-6 text-slate-900" aria-hidden="true">{{ auth()->user()->name }}</span>
                                <svg class="ml-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-slate-900/5 focus:outline-none" style="display: none;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-3 py-1 text-sm text-left leading-6 text-slate-900 hover:bg-slate-50">Sair (Logout)</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="flex-1 py-10 pt-6">
            <div class="px-4 sm:px-6 lg:px-8">
                
                @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-6 relative" x-data="{ show: true }" x-show="show">
                    <div class="flex">
                        <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg></div>
                        <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success') }}</p></div>
                        <div class="ml-auto pl-3"><div class="-mx-1.5 -my-1.5">
                            <button @click="show = false" type="button" class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100">
                                <span class="sr-only">Dismiss</span><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                            </button>
                        </div></div>
                    </div>
                </div>
                @endif
                
                @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6 relative" x-data="{ show: true }" x-show="show">
                    <div class="flex">
                        <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div>
                        <div class="ml-3"><p class="text-sm font-medium text-red-800">{{ session('error') }}</p></div>
                        <div class="ml-auto pl-3"><div class="-mx-1.5 -my-1.5">
                            <button @click="show = false" type="button" class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100"><span class="sr-only">Dismiss</span><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg></button>
                        </div></div>
                    </div>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @if(isset($adminNotifications) && $adminNotifications->isNotEmpty())
    <div class="fixed top-20 right-4 z-[70] space-y-3 w-full max-w-sm" x-data="adminAlerts()">
        @foreach($adminNotifications as $notification)
        <div
            x-show="visibleIds.includes({{ $notification->id }})"
            x-transition
            class="rounded-xl border border-red-200 bg-white/95 shadow-lg backdrop-blur p-4"
        >
            <div class="flex items-start gap-3">
                <div class="mt-0.5 h-10 w-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">!</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-900">{{ $notification->titulo }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $notification->mensagem }}</p>
                </div>
                <button @click="dismiss({{ $notification->id }})" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div id="admin-confirm-modal" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-slate-900/60"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 text-xl font-bold">!</div>
                        <div class="flex-1">
                            <h3 id="admin-confirm-title" class="text-lg font-bold text-slate-900">Confirmar ação</h3>
                            <p id="admin-confirm-message" class="mt-2 text-sm text-slate-600">Tem certeza que deseja continuar?</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 bg-slate-50 px-6 py-4 border-t border-slate-100">
                    <button id="admin-confirm-cancel" type="button" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg shadow-sm hover:bg-slate-50 transition-colors">Cancelar</button>
                    <button id="admin-confirm-ok" type="button" class="px-5 py-2 bg-red-600 text-white font-bold rounded-lg shadow-sm hover:bg-red-700 transition-colors">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        (() => {
            const modal = document.getElementById('admin-confirm-modal');
            const titleEl = document.getElementById('admin-confirm-title');
            const messageEl = document.getElementById('admin-confirm-message');
            const confirmButton = document.getElementById('admin-confirm-ok');
            const cancelButton = document.getElementById('admin-confirm-cancel');

            if (!modal || !titleEl || !messageEl || !confirmButton || !cancelButton) {
                return;
            }

            let resolver = null;

            const close = (result) => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');

                if (resolver) {
                    resolver(result);
                    resolver = null;
                }
            };

            confirmButton.addEventListener('click', () => close(true));
            cancelButton.addEventListener('click', () => close(false));
            modal.addEventListener('click', (event) => {
                if (event.target === modal || event.target === modal.firstElementChild) {
                    close(false);
                }
            });

            window.adminConfirm = function ({
                title = 'Confirmar ação',
                message = 'Tem certeza que deseja continuar?',
                confirmText = 'Confirmar',
                confirmClass = 'bg-red-600 hover:bg-red-700'
            } = {}) {
                titleEl.textContent = title;
                messageEl.textContent = message;
                confirmButton.textContent = confirmText;
                confirmButton.className = `px-5 py-2 text-white font-bold rounded-lg shadow-sm transition-colors ${confirmClass}`;
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                return new Promise((resolve) => {
                    resolver = resolve;
                });
            };

            window.adminConfirmSubmit = async function (event, options = {}) {
                event.preventDefault();
                const confirmed = await window.adminConfirm(options);

                if (confirmed) {
                    event.target.submit();
                }

                return false;
            };
        })();
    </script>
    @if(isset($adminNotifications) && $adminNotifications->isNotEmpty())
    <script>
        function adminAlerts() {
            return {
                visibleIds: @json($adminNotifications->pluck('id')->values()),
                async dismiss(id) {
                    this.visibleIds = this.visibleIds.filter((item) => item !== id);

                    try {
                        await fetch(`/admin/notificacoes/${id}/read`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                    } catch (error) {
                    }
                }
            }
        }
    </script>
    @endif
</body>
</html>
