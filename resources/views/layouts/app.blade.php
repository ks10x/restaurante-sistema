<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
        /* Fundo escuro para combinar com o cardápio */
            body, .min-h-screen {
                background-color: #1A1512 !important;
                color: #f3f4f6;
            }
            
            /* Ajuste nos cards brancos do Breeze */
            .bg-white {
                background-color: #2A2420 !important;
                border: 1px solid rgba(212, 163, 115, 0.1);
            }
            
            /* Textos e Labels */
            label, p, h2 {
                color: #D4A373 !important;
            }
            
            /* Inputs escuros */
            input {
                background-color: #1A1512 !important;
                border-color: #333 !important;
                color: white !important;
            }
        </style>

        <!-- Scripts -->
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
