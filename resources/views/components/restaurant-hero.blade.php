@props([
    'config',
])

@php
    $logoUrl = $config->logo_url;
@endphp

<section
    style="
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--border-soft);
        background: #ffffff;
        text-align: center;
    "
>
    <div
        style="
            position: absolute;
            inset: 0 0 auto 0;
            height: 176px;
            background: linear-gradient(180deg, color-mix(in srgb, var(--color-secondary) 8%, white 92%) 0%, rgba(255,255,255,0) 100%);
        "
    ></div>

    <div
        style="
            position: relative;
            max-width: 960px;
            min-height: 260px;
            margin: 0 auto;
            padding: 56px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            text-align: center;
        "
    >
        @if($logoUrl)
            <div
                style="
                    width: 112px;
                    height: 112px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    border-radius: 28px;
                    border: 1px solid var(--color-secondary-border);
                    background: #ffffff;
                    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
                "
            >
                <img
                    src="{{ $logoUrl }}"
                    alt="Logo do restaurante"
                    style="width: 100%; height: 100%; object-fit: contain;"
                >
            </div>
        @endif

        <div style="max-width: 760px; margin: 0 auto; text-align: center;">
            <h1
                style="
                    margin: 0 0 16px;
                    font-family: 'Playfair Display', serif;
                    font-size: clamp(2rem, 5vw, 3.5rem);
                    line-height: 1.1;
                    font-weight: 700;
                    color: var(--text-main);
                "
            >
                {{ $config->hero_title }}
            </h1>

            <p
                style="
                    margin: 0;
                    font-family: 'DM Sans', sans-serif;
                    font-size: 1.1rem;
                    line-height: 1.6;
                    color: var(--text-muted);
                "
            >
                {{ $config->hero_subtitle }}
            </p>
        </div>

        <div
            style="
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                gap: 12px;
                max-width: 100%;
                margin: 0 auto;
            "
        >
            <div
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    max-width: 100%;
                    padding: 12px 18px;
                    border-radius: 40px;
                    border: 1px solid var(--color-secondary-border);
                    background: #ffffff;
                    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
                    color: var(--text-main);
                    font: 600 14px/1.4 'DM Sans', sans-serif;
                "
            >
                <span style="color: var(--color-secondary); font-weight: 700;">Tempo</span>
                <span style="word-break: break-word;">{{ $config->delivery_time }}</span>
            </div>

            <div
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    max-width: 100%;
                    padding: 12px 18px;
                    border-radius: 40px;
                    border: 1px solid var(--color-secondary-border);
                    background: #ffffff;
                    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
                    color: var(--text-main);
                    font: 600 14px/1.4 'DM Sans', sans-serif;
                "
            >
                <span style="color: var(--color-secondary); font-weight: 700;">Local</span>
                <span style="word-break: break-word;">{{ $config->location_text }}</span>
            </div>

            <div
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    max-width: 100%;
                    padding: 12px 18px;
                    border-radius: 40px;
                    border: 1px solid var(--color-secondary-border);
                    background: #ffffff;
                    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
                    color: var(--text-main);
                    font: 600 14px/1.4 'DM Sans', sans-serif;
                "
            >
                <span style="color: var(--color-secondary); font-weight: 700;" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                <span style="word-break: break-word;">{{ number_format((float) $config->rating_score, 1, ',', '.') }} {{ $config->rating_label }}</span>
            </div>
        </div>
    </div>
</section>
