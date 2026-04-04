@php
    $viteManifest = public_path('build/manifest.json');
    $hasVite = file_exists($viteManifest);
@endphp

@if($hasVite)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <!-- Vite manifest not found: fallback for dev environments without build -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none !important;}</style>
@endif

