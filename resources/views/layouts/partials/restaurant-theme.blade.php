@php
    $restaurantConfig ??= \App\Models\RestaurantConfig::storefront();
    $secondaryColor = $restaurantConfig->secondary_color ?? '#002D72';
@endphp
<style>
    :root {
        --bg-primary: #FFFFFF;
        --color-secondary: {{ $secondaryColor }};
        --color-secondary-dark: color-mix(in srgb, var(--color-secondary) 84%, #0f172a 16%);
        --color-secondary-soft: color-mix(in srgb, var(--color-secondary) 10%, #ffffff 90%);
        --color-secondary-border: color-mix(in srgb, var(--color-secondary) 20%, #e2e8f0 80%);
        --text-main: #0f172a;
        --text-muted: #64748b;
        --surface-main: #ffffff;
        --surface-soft: #f8fafc;
        --surface-accent: color-mix(in srgb, var(--color-secondary) 6%, #ffffff 94%);
        --border-soft: #e2e8f0;
        --shadow-soft: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    body.high-contrast {
        --bg-primary: #000000 !important;
        --color-secondary: #FFFF00 !important;
        --color-secondary-dark: #FFFF00 !important;
        --text-main: #FFFFFF !important;
        --text-muted: #FFFFFF !important;
        --surface-main: #000000 !important;
        --surface-soft: #000000 !important;
        --surface-accent: #000000 !important;
        --border-soft: #FFFF00 !important;
    }

    .bg-custom-primary { background-color: var(--bg-primary); }
    .bg-custom-secondary-soft { background-color: var(--surface-accent); }
    .text-custom-secondary { color: var(--color-secondary); }
    .text-custom-main { color: var(--text-main); }
    .border-custom-secondary { border-color: var(--color-secondary); }

    .admin-shell {
        background: var(--bg-primary);
        color: var(--text-main);
    }

    .admin-sidebar {
        background: var(--surface-main) !important;
        border-right: 1px solid var(--border-soft);
        box-shadow: var(--shadow-soft);
    }

    .admin-sidebar-title {
        color: var(--text-main);
    }

    .admin-sidebar-badge {
        background: var(--surface-accent);
        color: var(--color-secondary-dark);
        border: 1px solid var(--color-secondary-border);
    }

    .admin-nav-link {
        color: var(--text-muted);
        transition: all .2s ease;
    }

    .admin-nav-link:hover,
    .admin-nav-link.is-active {
        background: var(--surface-accent);
        color: var(--color-secondary-dark);
    }

    .admin-nav-link svg {
        color: currentColor;
    }
</style>
