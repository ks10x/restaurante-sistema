@extends('layouts.admin')

@section('content')
@php
    $whiteLabel = auth()->user()->restaurantConfig ?? $restaurantConfig;
    $previewLogo = $whiteLabel->logo_url;
@endphp

<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="inline-block border-b-2 border-brand-500 pb-2 text-2xl font-bold text-slate-900">Configuracoes do Restaurante</h1>
            <p class="mt-2 text-sm text-slate-600">Gerencie operacao, acessibilidade e identidade visual do cardapio.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Nao foi possivel salvar as configuracoes.</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-1 text-lg font-bold text-slate-900">Aparencia do Painel</h2>
        <p class="text-sm text-slate-600">Preferencia salva neste navegador para tema e acessibilidade.</p>

        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Tema do painel</label>
                <select data-a11y-theme class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    <option value="blue">Azul</option>
                    <option value="green">Verde</option>
                    <option value="yellow">Amarelo</option>
                    <option value="orange">Laranja</option>
                    <option value="red">Vermelho</option>
                    <option value="purple">Roxo</option>
                    <option value="teal">Teal</option>
                    <option value="slate">Slate</option>
                </select>

                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" data-a11y-theme-btn="blue" class="h-8 w-8 rounded-full border border-slate-200" style="background:#2563eb" title="Azul"></button>
                    <button type="button" data-a11y-theme-btn="green" class="h-8 w-8 rounded-full border border-slate-200" style="background:#059669" title="Verde"></button>
                    <button type="button" data-a11y-theme-btn="yellow" class="h-8 w-8 rounded-full border border-slate-200" style="background:#d97706" title="Amarelo"></button>
                    <button type="button" data-a11y-theme-btn="orange" class="h-8 w-8 rounded-full border border-slate-200" style="background:#ea580c" title="Laranja"></button>
                    <button type="button" data-a11y-theme-btn="red" class="h-8 w-8 rounded-full border border-slate-200" style="background:#dc2626" title="Vermelho"></button>
                    <button type="button" data-a11y-theme-btn="purple" class="h-8 w-8 rounded-full border border-slate-200" style="background:#9333ea" title="Roxo"></button>
                    <button type="button" data-a11y-theme-btn="teal" class="h-8 w-8 rounded-full border border-slate-200" style="background:#0d9488" title="Teal"></button>
                    <button type="button" data-a11y-theme-btn="slate" class="h-8 w-8 rounded-full border border-slate-200" style="background:#475569" title="Slate"></button>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <div>
                        <div class="text-sm font-bold text-slate-900">Alto contraste</div>
                        <div class="text-xs text-slate-600">Sobrescreve as cores para acessibilidade.</div>
                    </div>
                    <button type="button" onclick="window.A11Y_PREFS?.toggle('contrast')" class="rounded-lg bg-brand-600 px-3 py-1.5 font-bold text-white transition hover:bg-brand-700">
                        Alternar
                    </button>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <div>
                        <div class="text-sm font-bold text-slate-900">Reduzir animacoes</div>
                        <div class="text-xs text-slate-600">Remove transicoes para navegacao mais estavel.</div>
                    </div>
                    <button type="button" onclick="window.A11Y_PREFS?.toggle('reduce_motion')" class="rounded-lg bg-brand-600 px-3 py-1.5 font-bold text-white transition hover:bg-brand-700">
                        Alternar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.configuracoes.white-label.update') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-slate-200 bg-white shadow-sm">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">White Label do Cardapio</h2>
                    <p class="mt-1 text-sm text-slate-600">A base do sistema fica branca e a cor secundaria se torna o destaque universal. O preview abaixo reage em tempo real.</p>
                </div>
                <div class="grid gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    <span><strong class="text-slate-900">Cor primaria:</strong> #FFFFFF (fixa)</span>
                    <span><strong class="text-slate-900">Cor secundaria:</strong> {{ $whiteLabel->secondary_color ?? '#002D72' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 xl:grid-cols-[1.05fr_0.95fr]">
            <div class="space-y-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Cor primaria</label>
                        <input type="text" value="#FFFFFF" class="w-full rounded-lg border border-slate-200 bg-slate-100 p-2.5 text-slate-500 outline-none" disabled>
                        <input type="hidden" name="primary_color" value="#FFFFFF">
                        <p class="mt-2 text-xs text-slate-500">A cor primaria do sistema fica fixa em branco para manter o layout universal.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Cor secundaria</label>
                        <input id="wl_secondary_color" type="text" name="secondary_color" value="{{ old('secondary_color', $whiteLabel->secondary_color ?? '#002D72') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500" placeholder="#002D72">
                        <p class="mt-2 text-xs text-slate-500">Use um hexadecimal como <code>#0F766E</code> ou <code>#D97706</code> para colorir botoes, links, sidebar e destaques.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Titulo principal</label>
                        <input id="wl_hero_title" type="text" name="hero_title" value="{{ old('hero_title', $whiteLabel->hero_title ?? 'Sabor que aquece a alma') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Subtitulo principal</label>
                        <input id="wl_hero_subtitle" type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $whiteLabel->hero_subtitle ?? 'A verdadeira essencia da gastronomia') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Tempo de entrega</label>
                        <input id="wl_delivery_time" type="text" name="delivery_time" value="{{ old('delivery_time', $whiteLabel->delivery_time ?? '~45 min') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Texto de localizacao</label>
                        <input id="wl_location_text" type="text" name="location_text" value="{{ old('location_text', $whiteLabel->location_text ?? 'Guaianases e regiao') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Nota</label>
                        <input id="wl_rating_score" type="number" step="0.1" min="0" max="9.9" name="rating_score" value="{{ old('rating_score', $whiteLabel->rating_score ?? '4.9') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Rotulo da avaliacao</label>
                        <input id="wl_rating_label" type="text" name="rating_label" value="{{ old('rating_label', $whiteLabel->rating_label ?? '(High Tech)') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Logo</label>
                    <input id="wl_logo" type="file" name="logo" accept="image/*" class="block w-full rounded-lg border border-dashed border-slate-300 bg-slate-50 p-3 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-brand-700">
                    <p class="mt-2 text-xs text-slate-500">PNG, JPG ou WebP com ate 2 MB. O arquivo enviado aparece no preview imediatamente.</p>
                </div>
            </div>

            <div class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-slate-500">Preview em tempo real</h3>

                <div id="wl_preview" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm" style="--preview-secondary: {{ $whiteLabel->secondary_color ?? '#002D72' }};">
                    <div class="border-b bg-white px-6 py-4" style="border-color: color-mix(in srgb, var(--preview-secondary) 15%, #e2e8f0 85%);">
                        <div class="flex items-center justify-between">
                            <div class="flex min-h-[48px] min-w-[120px] items-center">
                                <img
                                    id="wl_preview_logo"
                                    src="{{ $previewLogo ?? '' }}"
                                    alt="Logo preview"
                                    class="{{ $previewLogo ? '' : 'hidden' }} max-h-12 max-w-[180px] object-contain"
                                >
                                <span id="wl_preview_logo_fallback" class="{{ $previewLogo ? 'hidden' : '' }} text-2xl font-bold" style="font-family: 'Playfair Display', serif; color: var(--preview-secondary);">
                                    {{ config('app.name', 'Restaurante') }}
                                </span>
                            </div>
                            <button type="button" class="rounded-full px-4 py-2 text-sm font-bold text-white shadow-sm" style="background: linear-gradient(135deg, var(--preview-secondary) 0%, color-mix(in srgb, var(--preview-secondary) 84%, #0f172a 16%) 100%);">
                                Pedido
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-12 text-center" style="background: linear-gradient(180deg, color-mix(in srgb, var(--preview-secondary) 8%, white 92%) 0%, rgba(255,255,255,0) 100%);">
                        <div id="wl_preview_logo_wrap" class="mb-6 {{ $previewLogo ? '' : 'hidden' }}">
                            <img src="{{ $previewLogo ?? '' }}" alt="Logo central preview" class="mx-auto h-24 w-24 rounded-[28px] border bg-white p-3 shadow-sm object-contain" style="border-color: color-mix(in srgb, var(--preview-secondary) 18%, #e2e8f0 82%);">
                        </div>
                        <h2 id="wl_preview_title" class="mb-4 font-bold text-slate-900" style="font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3.5rem); line-height: 1.1;">
                            {{ $whiteLabel->hero_title ?? 'Sabor que aquece a alma' }}
                        </h2>
                        <p id="wl_preview_subtitle" class="mx-auto max-w-2xl text-[1.1rem] text-slate-500" style="font-family: 'DM Sans', sans-serif;">
                            {{ $whiteLabel->hero_subtitle ?? 'A verdadeira essencia da gastronomia' }}
                        </p>

                        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full border bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm" style="border-color: color-mix(in srgb, var(--preview-secondary) 18%, #e2e8f0 82%);">
                                <span style="color: var(--preview-secondary);">Tempo</span>
                                <span id="wl_preview_delivery">{{ $whiteLabel->delivery_time ?? '~45 min' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full border bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm" style="border-color: color-mix(in srgb, var(--preview-secondary) 18%, #e2e8f0 82%);">
                                <span style="color: var(--preview-secondary);">Local</span>
                                <span id="wl_preview_location">{{ $whiteLabel->location_text ?? 'Guaianases e regiao' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full border bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm" style="border-color: color-mix(in srgb, var(--preview-secondary) 18%, #e2e8f0 82%);">
                                <span style="color: var(--preview-secondary);">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                                <span id="wl_preview_rating">{{ ($whiteLabel->rating_score ?? '4.9').' '.($whiteLabel->rating_label ?? '(High Tech)') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end border-t border-slate-100 bg-slate-50 px-6 py-4">
            <button type="submit" class="rounded-lg bg-brand-600 px-8 py-2.5 font-bold text-white shadow-sm transition hover:bg-brand-700">
                Salvar White Label
            </button>
        </div>
    </form>

    <form action="{{ route('admin.configuracoes.store') }}" method="POST" class="rounded-xl border border-slate-200 bg-white shadow-sm">
        @csrf

        <div class="border-b border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-900">Operacao do Restaurante</h2>
            <p class="mt-1 text-sm text-slate-600">Configuracoes operacionais legadas continuam separadas da identidade visual.</p>
        </div>

        <div class="space-y-6 p-6">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4" x-data="{ aberto: {{ $config['restaurante_aberto'] == '1' ? 'true' : 'false' }} }">
                <div class="flex items-center gap-3">
                    <button type="button" @click="aberto = !aberto" class="relative inline-flex h-6 w-11 flex-shrink-0 rounded-full border-2 border-transparent transition-colors duration-200" :class="aberto ? 'bg-emerald-500' : 'bg-slate-300'">
                        <input type="checkbox" name="restaurante_aberto" class="hidden" x-model="aberto">
                        <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out" :class="aberto ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                    <div>
                        <span class="text-sm font-bold text-slate-900" x-text="aberto ? 'Restaurante aberto' : 'Restaurante fechado'"></span>
                        <p class="text-xs text-slate-500">Ao fechar, novos pedidos ficam indisponiveis no site.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Nome do restaurante</label>
                    <input type="text" name="restaurante_nome" value="{{ $config['restaurante_nome'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">WhatsApp ou telefone</label>
                    <input type="text" name="restaurante_telefone" value="{{ $config['restaurante_telefone'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Aviso no topo</label>
                    <input type="text" name="mensagem_aviso" value="{{ $config['mensagem_aviso'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Taxa de entrega padrao</label>
                    <input type="number" step="0.01" name="taxa_entrega_padrao" value="{{ $config['taxa_entrega_padrao'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Tempo estimado em minutos</label>
                    <input type="number" name="tempo_estimado_minutos" value="{{ $config['tempo_estimado_minutos'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-slate-900 outline-none focus:border-brand-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end border-t border-slate-100 bg-slate-50 px-6 py-4">
            <button type="submit" class="rounded-lg bg-brand-600 px-8 py-2.5 font-bold text-white shadow-sm transition hover:bg-brand-700">
                Salvar Operacao
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const secondaryInput = document.getElementById('wl_secondary_color');
        const titleInput = document.getElementById('wl_hero_title');
        const subtitleInput = document.getElementById('wl_hero_subtitle');
        const deliveryInput = document.getElementById('wl_delivery_time');
        const locationInput = document.getElementById('wl_location_text');
        const ratingScoreInput = document.getElementById('wl_rating_score');
        const ratingLabelInput = document.getElementById('wl_rating_label');
        const logoInput = document.getElementById('wl_logo');
        const preview = document.getElementById('wl_preview');

        if (!preview) {
            return;
        }

        const els = {
            title: document.getElementById('wl_preview_title'),
            subtitle: document.getElementById('wl_preview_subtitle'),
            delivery: document.getElementById('wl_preview_delivery'),
            location: document.getElementById('wl_preview_location'),
            rating: document.getElementById('wl_preview_rating'),
            topLogo: document.getElementById('wl_preview_logo'),
            topFallback: document.getElementById('wl_preview_logo_fallback'),
            centerWrap: document.getElementById('wl_preview_logo_wrap'),
        };

        const isHex = (value) => /^#[0-9A-Fa-f]{6}$/.test(value || '');

        const updatePreview = () => {
            const secondary = isHex(secondaryInput?.value) ? secondaryInput.value : '#002D72';
            preview.style.setProperty('--preview-secondary', secondary);

            if (els.title && titleInput) els.title.textContent = titleInput.value || 'Sabor que aquece a alma';
            if (els.subtitle && subtitleInput) els.subtitle.textContent = subtitleInput.value || 'A verdadeira essencia da gastronomia';
            if (els.delivery && deliveryInput) els.delivery.textContent = deliveryInput.value || '~45 min';
            if (els.location && locationInput) els.location.textContent = locationInput.value || 'Guaianases e regiao';
            if (els.rating) {
                const score = ratingScoreInput?.value || '4.9';
                const label = ratingLabelInput?.value || '(High Tech)';
                els.rating.textContent = `${score} ${label}`;
            }
        };

        [secondaryInput, titleInput, subtitleInput, deliveryInput, locationInput, ratingScoreInput, ratingLabelInput].forEach((input) => {
            input?.addEventListener('input', updatePreview);
        });

        logoInput?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = () => {
                const url = reader.result;
                if (typeof url !== 'string') {
                    return;
                }

                if (els.topLogo) {
                    els.topLogo.src = url;
                    els.topLogo.classList.remove('hidden');
                }

                if (els.topFallback) {
                    els.topFallback.classList.add('hidden');
                }

                if (els.centerWrap) {
                    els.centerWrap.classList.remove('hidden');
                    els.centerWrap.innerHTML = `<img src="${url}" alt="Logo central preview" class="mx-auto h-24 w-24 rounded-[28px] border bg-white p-3 shadow-sm object-contain" style="border-color: color-mix(in srgb, var(--preview-secondary) 18%, #e2e8f0 82%);">`;
                }
            };

            reader.readAsDataURL(file);
        });

        updatePreview();
    })();
</script>
@endpush
