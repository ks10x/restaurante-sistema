<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro — {{ config('app.name', 'Restaurante') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>
    @include('layouts.partials.restaurant-theme')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: var(--surface-soft); font-family: 'DM Sans', sans-serif; }
        .brand-text  { color: var(--color-secondary); }
        .brand-bg    { background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-dark) 100%); }
        .brand-bg-hover:hover { filter: brightness(0.9); }
        .brand-border:focus { border-color: var(--color-secondary); box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-secondary) 15%, transparent); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-6">
    <div class="w-full max-w-2xl bg-white rounded-[32px] p-8 border shadow-xl relative mt-10" style="border-color: var(--color-secondary-border);">
        <a href="{{ route('cardapio.index') }}" class="absolute top-8 left-8 inline-flex items-center text-slate-400 hover:text-slate-700 transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Voltar ao Cardápio</span>
        </a>

        <div class="text-center mb-6 mt-4">
            @if($restaurantConfig->logo_url)
                <img src="{{ $restaurantConfig->logo_url }}" alt="Logo do restaurante" class="mx-auto mb-4 max-h-16 max-w-[180px] object-contain">
            @endif
            <h1 class="text-3xl font-bold brand-text mb-2" style="font-family: 'Playfair Display', serif;">{{ config('app.name', 'Restaurante') }}</h1>
            <p class="text-slate-500 text-sm">Crie sua conta em 3 etapas.</p>
        </div>

        {{-- Steps --}}
        <div class="flex items-center justify-between gap-3 mb-6">
            <div class="flex-1 flex items-center gap-3">
                <div id="stepDot1" class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border border-slate-200 bg-white text-slate-600">1</div>
                <div class="min-w-0">
                    <div class="text-xs font-bold uppercase text-slate-400">Etapa 1</div>
                    <div class="text-sm font-semibold text-slate-800">Dados pessoais</div>
                </div>
            </div>
            <div class="h-px bg-slate-200 flex-1"></div>
            <div class="flex-1 flex items-center gap-3">
                <div id="stepDot2" class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border border-slate-200 bg-white text-slate-600">2</div>
                <div class="min-w-0">
                    <div class="text-xs font-bold uppercase text-slate-400">Etapa 2</div>
                    <div class="text-sm font-semibold text-slate-800">Endereço</div>
                </div>
            </div>
            <div class="h-px bg-slate-200 flex-1"></div>
            <div class="flex-1 flex items-center gap-3">
                <div id="stepDot3" class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border border-slate-200 bg-white text-slate-600">3</div>
                <div class="min-w-0">
                    <div class="text-xs font-bold uppercase text-slate-400">Etapa 3</div>
                    <div class="text-sm font-semibold text-slate-800">Segurança</div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Ops! Verifique os dados abaixo:</h3>
                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="registerWizard" method="POST" action="{{ route('register') }}" class="space-y-6" novalidate>
            @csrf

            {{-- Etapa 1: Dados pessoais --}}
            <div id="step1" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Nome*</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required class="brand-border w-full bg-slate-50 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Sobrenome*</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="brand-border w-full bg-slate-50 border @error('last_name') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Telefone*</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="(11) 90000-0000" class="brand-border w-full bg-slate-50 border @error('phone') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">CPF*</label>
                        <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required placeholder="000.000.000-00" class="brand-border w-full bg-slate-50 border @error('cpf') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                        <p class="mt-1 text-[11px] text-slate-400">O CPF será validado automaticamente.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Data de nascimento*</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select id="birth_day" name="birth_day" required class="brand-border w-full bg-slate-50 border @error('birth_day') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                                <option value="">Dia</option>
                                @for($d=1;$d<=31;$d++)
                                    <option value="{{ $d }}" @selected((string)old('birth_day') === (string)$d)>{{ $d }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            @php($meses = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'])
                            <select id="birth_month" name="birth_month" required class="brand-border w-full bg-slate-50 border @error('birth_month') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                                <option value="">Mês</option>
                                @foreach($meses as $num=>$label)
                                    <option value="{{ $num }}" @selected((string)old('birth_month') === (string)$num)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            @php($anoAtual = (int) now()->year)
                            <select id="birth_year" name="birth_year" required class="brand-border w-full bg-slate-50 border @error('birth_year') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                                <option value="">Ano</option>
                                @for($y=$anoAtual;$y>=1900;$y--)
                                    <option value="{{ $y }}" @selected((string)old('birth_year') === (string)$y)>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">E-mail*</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="brand-border w-full bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                </div>
            </div>

            {{-- Etapa 2: Endereço --}}
            <div id="step2" class="space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">CEP*</label>
                        <input id="cep" type="text" name="cep" value="{{ old('cep') }}" required placeholder="00000-000" class="brand-border w-full bg-slate-50 border @error('cep') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                        <div id="cepHint" class="mt-1 text-[11px] text-slate-400"></div>
                    </div>
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Número*</label>
                        <input id="numero" type="text" name="numero" value="{{ old('numero') }}" required class="brand-border w-full bg-slate-50 border @error('numero') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Rua (Logradouro)*</label>
                    <input id="logradouro" type="text" name="logradouro" value="{{ old('logradouro') }}" required class="brand-border w-full bg-slate-50 border @error('logradouro') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Bairro*</label>
                        <input id="bairro" type="text" name="bairro" value="{{ old('bairro') }}" required class="brand-border w-full bg-slate-50 border @error('bairro') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Cidade*</label>
                        <input id="cidade" type="text" name="cidade" value="{{ old('cidade') }}" required class="brand-border w-full bg-slate-50 border @error('cidade') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">UF*</label>
                        <input id="estado" type="text" name="estado" value="{{ old('estado') }}" required maxlength="2" placeholder="SP" class="brand-border uppercase w-full bg-slate-50 border @error('estado') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Complemento</label>
                        <input id="complemento" type="text" name="complemento" value="{{ old('complemento') }}" class="brand-border w-full bg-slate-50 border @error('complemento') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Etapa 3: Segurança --}}
            <div id="step3" class="space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Senha*</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required class="brand-border w-full bg-slate-50 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 pr-12 text-slate-900 outline-none transition-all">
                            <button type="button" data-toggle-password="password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div id="pwdBar" class="h-full w-0 bg-red-500 transition-all"></div>
                            </div>
                            <div id="pwdHint" class="mt-1 text-[11px] text-slate-400"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Confirmar senha*</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="brand-border w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 pr-12 text-slate-900 outline-none transition-all">
                            <button type="button" data-toggle-password="password_confirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="pwdMatchHint" class="mt-1 text-[11px] text-slate-400"></div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    Ao criar sua conta, você concorda em fornecer dados verdadeiros para entrega e suporte.
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-slate-700">Já tem conta?</a>
                    <button id="btnNext" type="button" class="brand-bg brand-bg-hover px-6 py-3 text-white font-bold rounded-2xl transition-all shadow-lg">
                        Avançar
                    </button>
                    <button id="btnSubmit" type="submit" class="brand-bg brand-bg-hover hidden px-6 py-3 text-white font-bold rounded-2xl transition-all shadow-lg">
                        Finalizar cadastro
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const errorFields = @json($errors->keys());

        // Toggle password visibility
        document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-toggle-password');
                const input = document.getElementById(id);
                if (!input) return;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });

        const stepEl = {
            1: document.getElementById('step1'),
            2: document.getElementById('step2'),
            3: document.getElementById('step3'),
        };

        const stepDots = {
            1: document.getElementById('stepDot1'),
            2: document.getElementById('stepDot2'),
            3: document.getElementById('stepDot3'),
        };

        const btnNext   = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');

        const fieldsStep2 = ['cep','logradouro','bairro','cidade','estado','numero'];
        const fieldsStep3 = ['password','password_confirmation'];

        let currentStep = 1;

        // Lê a cor primária do white label para aplicar nos dots via JS
        const brandColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--color-secondary').trim() || '#1E3A8A';

        function initialStepFromErrors() {
            if (!errorFields || errorFields.length === 0) return 1;
            if (errorFields.some(f => fieldsStep3.includes(f))) return 3;
            if (errorFields.some(f => fieldsStep2.includes(f))) return 2;
            return 1;
        }

        function setStep(step) {
            currentStep = step;
            Object.values(stepEl).forEach(el => el.classList.add('hidden'));
            stepEl[step].classList.remove('hidden');

            Object.entries(stepDots).forEach(([k, dot]) => {
                const s = Number(k);
                // Reset
                dot.style.background = '';
                dot.style.color = '';
                dot.style.borderColor = '';
                dot.classList.remove('text-white');
                dot.classList.add('bg-white', 'text-slate-600', 'border-slate-200');

                if (s <= step) {
                    // Ativo ou concluído — usa a cor do white label via style inline
                    dot.style.background = brandColor;
                    dot.style.color = '#ffffff';
                    dot.style.borderColor = brandColor;
                    dot.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
                }

                dot.textContent = s < step ? '✓' : String(s);
            });

            btnNext.classList.toggle('hidden', step === 3);
            btnSubmit.classList.toggle('hidden', step !== 3);
        }

        function getValue(id) {
            const el = document.getElementById(id);
            return el ? String(el.value || '').trim() : '';
        }

        function validateStep(step) {
            if (step === 1) {
                const required = ['name','last_name','phone','cpf','birth_day','birth_month','birth_year','email'];
                return required.every(id => getValue(id) !== '');
            }
            if (step === 2) {
                return fieldsStep2.every(id => getValue(id) !== '');
            }
            if (step === 3) {
                const pwd = getValue('password');
                const conf = getValue('password_confirmation');
                if (!pwd || !conf) return false;
                return pwd === conf;
            }
            return true;
        }

        btnNext.addEventListener('click', () => {
            if (!validateStep(currentStep)) {
                alert('Preencha corretamente os campos obrigatórios desta etapa.');
                return;
            }
            setStep(currentStep + 1);
        });

        // Máscaras
        function onlyDigits(v) { return (v || '').replace(/\D/g, ''); }

        function maskCpf(v) {
            const d = onlyDigits(v).slice(0, 11);
            return d
                .replace(/^(\d{3})(\d)/, '$1.$2')
                .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
        }

        function maskCep(v) {
            const d = onlyDigits(v).slice(0, 8);
            return d.replace(/^(\d{5})(\d)/, '$1-$2');
        }

        function maskPhone(v) {
            const d = onlyDigits(v).slice(0, 11);
            if (d.length <= 10) {
                return d.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
            }
            return d.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
        }

        const cpfEl    = document.getElementById('cpf');
        const cepEl    = document.getElementById('cep');
        const phoneEl  = document.getElementById('phone');
        const estadoEl = document.getElementById('estado');

        cpfEl?.addEventListener('input', (e) => { e.target.value = maskCpf(e.target.value); });
        cepEl?.addEventListener('input', (e) => { e.target.value = maskCep(e.target.value); });
        phoneEl?.addEventListener('input', (e) => { e.target.value = maskPhone(e.target.value); });
        estadoEl?.addEventListener('input', (e) => { e.target.value = (e.target.value || '').toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2); });

        // ViaCEP
        const cepHint = document.getElementById('cepHint');
        let lastCepLookup = null;

        async function lookupCep() {
            const cepDigits = onlyDigits(getValue('cep'));
            if (cepDigits.length !== 8) return;
            if (cepDigits === lastCepLookup) return;
            lastCepLookup = cepDigits;
            cepHint.textContent = 'Buscando CEP...';
            try {
                const resp = await fetch(`https://viacep.com.br/ws/${cepDigits}/json/`);
                const data = await resp.json();
                if (data.erro) { cepHint.textContent = 'CEP não encontrado. Você pode preencher manualmente.'; return; }
                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('bairro').value     = data.bairro || '';
                document.getElementById('cidade').value     = data.localidade || '';
                document.getElementById('estado').value     = (data.uf || '').toUpperCase();
                cepHint.textContent = 'Endereço preenchido automaticamente. Você pode editar se quiser.';
            } catch (e) {
                cepHint.textContent = 'Erro ao consultar ViaCEP. Preencha manualmente.';
            }
        }

        cepEl?.addEventListener('blur', lookupCep);
        cepEl?.addEventListener('keyup', () => { if (onlyDigits(getValue('cep')).length === 8) lookupCep(); });

        // Força da senha
        const pwdEl      = document.getElementById('password');
        const pwdConfEl  = document.getElementById('password_confirmation');
        const pwdBar     = document.getElementById('pwdBar');
        const pwdHint    = document.getElementById('pwdHint');
        const pwdMatchHint = document.getElementById('pwdMatchHint');

        function scorePassword(pwd) {
            let score = 0;
            if (pwd.length >= 8) score++;
            if (/[A-Z]/.test(pwd)) score++;
            if (/[a-z]/.test(pwd)) score++;
            if (/\d/.test(pwd)) score++;
            if (/[^A-Za-z0-9]/.test(pwd)) score++;
            return score;
        }

        function updatePasswordUi() {
            const pwd  = getValue('password');
            const conf = getValue('password_confirmation');
            const score = scorePassword(pwd);
            const pct = Math.min(100, Math.round((score / 5) * 100));

            pwdBar.style.width = `${pct}%`;
            pwdBar.className = 'h-full transition-all ' + (pct >= 80 ? 'bg-emerald-500' : pct >= 60 ? 'bg-amber-500' : 'bg-red-500');

            pwdHint.textContent =
                score >= 4 ? 'Senha forte.' :
                score >= 3 ? 'Senha média. Adicione mais complexidade.' :
                             'Senha fraca. Use 8+ caracteres com letras, números e símbolos.';

            if (!conf) {
                pwdMatchHint.textContent = '';
                pwdMatchHint.className = 'mt-1 text-[11px] text-slate-400';
            } else if (pwd === conf) {
                pwdMatchHint.textContent = 'Senhas conferem.';
                pwdMatchHint.className = 'mt-1 text-[11px] text-emerald-600';
            } else {
                pwdMatchHint.textContent = 'As senhas não conferem.';
                pwdMatchHint.className = 'mt-1 text-[11px] text-red-600';
            }
        }

        pwdEl?.addEventListener('input', updatePasswordUi);
        pwdConfEl?.addEventListener('input', updatePasswordUi);

        setStep(initialStepFromErrors());
        updatePasswordUi();
    </script>
</body>
</html>