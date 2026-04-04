(() => {
  const KEY_CONTRAST = 'pref_contrast';
  const KEY_REDUCE_MOTION = 'pref_reduce_motion';

  function isOn(key) {
    try {
      return localStorage.getItem(key) === '1';
    } catch {
      return false;
    }
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

    document.documentElement.classList.toggle('pref-contrast', contrast);
    document.documentElement.classList.toggle('pref-reduce-motion', reduceMotion);

    // High contrast CSS that works even in pages without CSS variables.
    ensureStyle(
      'a11y-contrast-style',
      contrast
        ? `
        html, body { background: #0b1220 !important; color: #f8fafc !important; }
        a { color: #93c5fd !important; }
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

  window.A11Y_PREFS = {
    apply,
    toggle,
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', apply);
  } else {
    apply();
  }
})();

