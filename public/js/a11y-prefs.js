(() => {
  const KEY_CONTRAST = 'pref_contrast';
  const KEY_REDUCE_MOTION = 'pref_reduce_motion';
  const KEY_THEME = 'pref_theme';

  const THEMES = {
    blue:   { name: 'Azul',    brand50: '#eff6ff', brand100: '#dbeafe', brand500: '#3b82f6', brand600: '#2563eb', brand700: '#1d4ed8', brand900: '#1e3a8a' },
    green:  { name: 'Verde',   brand50: '#ecfdf5', brand100: '#d1fae5', brand500: '#10b981', brand600: '#059669', brand700: '#047857', brand900: '#064e3b' },
    yellow: { name: 'Amarelo', brand50: '#fffbeb', brand100: '#fef3c7', brand500: '#f59e0b', brand600: '#d97706', brand700: '#b45309', brand900: '#78350f' },
    orange: { name: 'Laranja', brand50: '#fff7ed', brand100: '#ffedd5', brand500: '#f97316', brand600: '#ea580c', brand700: '#c2410c', brand900: '#7c2d12' },
    red:    { name: 'Vermelho',brand50: '#fef2f2', brand100: '#fee2e2', brand500: '#ef4444', brand600: '#dc2626', brand700: '#b91c1c', brand900: '#7f1d1d' },
    purple: { name: 'Roxo',    brand50: '#faf5ff', brand100: '#f3e8ff', brand500: '#a855f7', brand600: '#9333ea', brand700: '#7e22ce', brand900: '#581c87' },
    teal:   { name: 'Teal',    brand50: '#f0fdfa', brand100: '#ccfbf1', brand500: '#14b8a6', brand600: '#0d9488', brand700: '#0f766e', brand900: '#134e4a' },
    slate:  { name: 'Slate',   brand50: '#f8fafc', brand100: '#f1f5f9', brand500: '#64748b', brand600: '#475569', brand700: '#334155', brand900: '#0f172a' },
  };

  function isOn(key) {
    try {
      return localStorage.getItem(key) === '1';
    } catch {
      return false;
    }
  }

  function getTheme() {
    try {
      const value = localStorage.getItem(KEY_THEME);
      return value && THEMES[value] ? value : 'blue';
    } catch {
      return 'blue';
    }
  }

  function setTheme(value) {
    const safe = value && THEMES[value] ? value : 'blue';
    try {
      localStorage.setItem(KEY_THEME, safe);
    } catch {
      // ignore
    }
  }

  function hexToRgbTriplet(hex) {
    const normalized = String(hex || '').replace('#', '').trim();
    if (normalized.length !== 6) return '59 130 246'; // fallback (blue-500)
    const r = parseInt(normalized.slice(0, 2), 16);
    const g = parseInt(normalized.slice(2, 4), 16);
    const b = parseInt(normalized.slice(4, 6), 16);
    if (Number.isNaN(r) || Number.isNaN(g) || Number.isNaN(b)) return '59 130 246';
    return `${r} ${g} ${b}`;
  }

  function setOn(key, value) {
    try {
      localStorage.setItem(key, value ? '1' : '0');
    } catch {
      // ignore
    }
  }

  function ensureStyle(id, cssText) {
    let el = document.getElementById(id);
    if (!el) {
      el = document.createElement('style');
      el.id = id;
      document.head.appendChild(el);
    }
    el.textContent = cssText || '';
  }

  function apply() {
    const contrast = isOn(KEY_CONTRAST);
    const reduceMotion = isOn(KEY_REDUCE_MOTION);
    const themeKey = getTheme();
    const theme = THEMES[themeKey] || THEMES.blue;

    document.documentElement.classList.toggle('pref-contrast', contrast);
    document.documentElement.classList.toggle('pref-reduce-motion', reduceMotion);
    document.documentElement.dataset.theme = themeKey;
    if (document.body) {
      document.body.classList.toggle('pref-contrast', contrast);
      document.body.classList.toggle('pref-reduce-motion', reduceMotion);
      document.body.classList.toggle('high-contrast', contrast);
      document.body.dataset.theme = themeKey;
    }

    // Theme variables (accent) – used by custom pages (CSS vars) and by Tailwind class overrides below.
    const rootStyle = document.documentElement.style;
    rootStyle.setProperty('--brand-50', theme.brand50);
    rootStyle.setProperty('--brand-100', theme.brand100);
    rootStyle.setProperty('--brand-500', theme.brand500);
    rootStyle.setProperty('--brand-600', theme.brand600);
    rootStyle.setProperty('--brand-700', theme.brand700);
    rootStyle.setProperty('--brand-900', theme.brand900);
    rootStyle.setProperty('--brand-500-rgb', hexToRgbTriplet(theme.brand500));
    rootStyle.setProperty('--brand-600-rgb', hexToRgbTriplet(theme.brand600));

    // Legacy vars used in some pages
    rootStyle.setProperty('--brand', theme.brand600);
    rootStyle.setProperty('--brand-d', theme.brand700);

    ensureStyle(
      'a11y-theme-style',
      `
      :root { --a11y-link: var(--brand-500); }
      /* Tailwind brand overrides (CDN tailwind + compiled classes) */
      .bg-brand-50 { background-color: var(--brand-50) !important; }
      .bg-brand-100 { background-color: var(--brand-100) !important; }
      .bg-brand-500 { background-color: var(--brand-500) !important; }
      .bg-brand-600 { background-color: var(--brand-600) !important; }
      .bg-brand-700 { background-color: var(--brand-700) !important; }
      .bg-brand-900 { background-color: var(--brand-900) !important; }

      .text-brand-500 { color: var(--brand-500) !important; }
      .text-brand-600 { color: var(--brand-600) !important; }
      .text-brand-700 { color: var(--brand-700) !important; }
      .text-brand-900 { color: var(--brand-900) !important; }

      .border-brand-200 { border-color: rgb(var(--brand-500-rgb) / 0.20) !important; }
      .border-brand-500 { border-color: var(--brand-500) !important; }
      .border-brand-600 { border-color: var(--brand-600) !important; }
      .border-brand-700 { border-color: var(--brand-700) !important; }

      .ring-brand-500 { --tw-ring-color: var(--brand-500) !important; }
      .focus\\:ring-brand-500:focus { --tw-ring-color: var(--brand-500) !important; }
      .focus\\:border-brand-500:focus { border-color: var(--brand-500) !important; }

      .hover\\:bg-brand-50:hover { background-color: var(--brand-50) !important; }
      .hover\\:bg-brand-100:hover { background-color: var(--brand-100) !important; }
      .hover\\:bg-brand-700:hover { background-color: var(--brand-700) !important; }
      .hover\\:text-brand-600:hover { color: var(--brand-600) !important; }
      .hover\\:text-brand-700:hover { color: var(--brand-700) !important; }
      .hover\\:text-brand-900:hover { color: var(--brand-900) !important; }

      /* Common alpha utilities */
      .bg-brand-500\\/10 { background-color: rgb(var(--brand-500-rgb) / 0.10) !important; }
      .bg-brand-500\\/20 { background-color: rgb(var(--brand-500-rgb) / 0.20) !important; }
      .ring-brand-500\\/20 { --tw-ring-color: rgb(var(--brand-500-rgb) / 0.20) !important; }
      .ring-brand-500\\/30 { --tw-ring-color: rgb(var(--brand-500-rgb) / 0.30) !important; }
    `.trim()
    );

    // High contrast CSS that works even in pages without CSS variables.
    ensureStyle(
      'a11y-contrast-style',
      contrast
        ? `
        html, body { background: #0b1220 !important; color: #f8fafc !important; }
        a { color: var(--a11y-link) !important; }
        .modal-content, .card, .panel, .section-card, .profile-card { background: #0f172a !important; color: #f8fafc !important; border-color: rgba(148,163,184,.22) !important; }
        .menu-item:hover { background: rgba(255,255,255,0.04) !important; }
        .menu-text .subtitle, .muted, .profile-email, .section-title, .row-sub, .help { color: #cbd5e1 !important; }
        input, select, textarea { background: #111b2e !important; color: #f8fafc !important; border-color: rgba(148,163,184,.22) !important; }
        input::placeholder, textarea::placeholder { color: rgba(203,213,225,.7) !important; }
        table, th, td { color: #f8fafc !important; border-color: rgba(148,163,184,.18) !important; }
      `
        : ''
    );

    ensureStyle(
      'a11y-reduce-motion-style',
      reduceMotion
        ? `
        *, *::before, *::after { transition: none !important; animation: none !important; scroll-behavior: auto !important; }
      `
        : ''
    );

    // Sync legacy toggles (if present)
    document.querySelectorAll('[data-a11y-toggle="contrast"]').forEach((el) => {
      el.classList.toggle('active', contrast);
    });
    document.querySelectorAll('[data-a11y-toggle="reduce_motion"]').forEach((el) => {
      el.classList.toggle('active', reduceMotion);
    });

    // Theme controls (select / buttons)
    document.querySelectorAll('[data-a11y-theme]').forEach((el) => {
      if (el && typeof el.value !== 'undefined') {
        el.value = themeKey;
      }
      if (!el.__a11yThemeWired) {
        el.__a11yThemeWired = true;
        el.addEventListener('change', () => setThemeAndApply(el.value));
      }
    });
    document.querySelectorAll('[data-a11y-theme-btn]').forEach((el) => {
      const key = el.getAttribute('data-a11y-theme-btn');
      const isActive = key === themeKey;
      el.classList.toggle('active', isActive);
      el.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      el.style.boxShadow = isActive ? '0 0 0 3px rgb(var(--brand-500-rgb) / 0.55)' : '';
      if (!el.__a11yThemeBtnWired) {
        el.__a11yThemeBtnWired = true;
        el.addEventListener('click', () => setThemeAndApply(key));
      }
    });
  }

  function toggle(key) {
    if (key === 'contrast') {
      setOn(KEY_CONTRAST, !isOn(KEY_CONTRAST));
    }
    if (key === 'reduce_motion') {
      setOn(KEY_REDUCE_MOTION, !isOn(KEY_REDUCE_MOTION));
    }
    apply();
  }

  function setThemeAndApply(value) {
    setTheme(value);
    apply();
  }

  window.A11Y_PREFS = {
    apply,
    toggle,
    getTheme,
    setTheme: setThemeAndApply,
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', apply);
  } else {
    apply();
  }
})();
