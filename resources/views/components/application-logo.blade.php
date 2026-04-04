@php
    $config = $restaurantConfig ?? \App\Models\RestaurantConfig::storefront();
@endphp

@if($config->logo_url)
    <img src="{{ $config->logo_url }}" alt="Logo do restaurante" {{ $attributes->merge(['class' => 'object-contain']) }}>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }} style="color: var(--color-secondary); font-family: 'Playfair Display', serif; font-weight: 700;">
        {{ config('app.name', 'Restaurante') }}
    </div>
@endif
