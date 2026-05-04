  const dot  = document.getElementById('cDot');
  const ring = document.getElementById('cRing');
  document.addEventListener('mousemove', e => {
    dot.style.left  = e.clientX + 'px';
    dot.style.top   = e.clientY + 'px';
    ring.style.left = e.clientX + 'px';
    ring.style.top  = e.clientY + 'px';
  });
  document.addEventListener('mousedown', () => { dot.style.transform = 'translate(-50%,-50%) scale(1.8)'; });
  document.addEventListener('mouseup',   () => { dot.style.transform = 'translate(-50%,-50%) scale(1)'; });

  /* ═══════════════════════════════════════════════
     SESSION HELPERS
  ═══════════════════════════════════════════════ */
  function getUserSession() {
    try { return JSON.parse(localStorage.getItem('bisita_user')); }
    catch(e) { return null; }
  }
  function setUserSession(data) {
    localStorage.setItem('bisita_user', JSON.stringify(data));
  }
  function clearUserSession() {
    localStorage.removeItem('bisita_user');
  }
  function applyUserUI(name) {
    const init = name ? name[0].toUpperCase() : '?';
    const display = name ? name.charAt(0).toUpperCase() + name.slice(1) : 'User';
    document.getElementById('uAv').textContent   = init;
    document.getElementById('uName').textContent = display;
    const hUid = document.getElementById('hUserId');
    const tUid = document.getElementById('tUserId');
    if (hUid) hUid.value = name || '';
    if (tUid) tUid.value = name || '';
  }

  /* ═══════════════════════════════════════════════
     PAGE NAVIGATION (UI-only — no form logic here)
  ═══════════════════════════════════════════════ */
  function showPage(id) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    window.scrollTo(0, 0);
  }

  // Sign In — ALWAYS show login page, never skip
  document.getElementById('goLogin').addEventListener('click', () => {
    showPage('pgLogin');
  });

  // Take Survey — skip to app only if already logged in
  document.getElementById('goSurveyHome').addEventListener('click', () => {
    const sess = getUserSession();
    if (sess && sess.name) { showPage('pgApp'); return; }
    showPage('pgLogin');
  });

  // Login ↔ Register
  document.getElementById('goReg').addEventListener('click', e => { e.preventDefault(); showPage('pgReg'); });
  document.getElementById('goLog').addEventListener('click', e => { e.preventDefault(); showPage('pgLogin'); });

  // Back buttons
  document.getElementById('backHome1').addEventListener('click', () => showPage('pgHome'));
  document.getElementById('backHome2').addEventListener('click', () => showPage('pgHome'));

  /* ═══════════════════════════════════════════════
     PASSWORD SHOW/HIDE
  ═══════════════════════════════════════════════ */
  document.getElementById('tPw').addEventListener('click', function() {
    const pw = document.getElementById('lPw');
    const shown = pw.type === 'text';
    pw.type = shown ? 'password' : 'text';
    this.textContent = shown ? 'show' : 'hide';
  });

  /* ═══════════════════════════════════════════════
     APP TABS
  ═══════════════════════════════════════════════ */
  document.querySelectorAll('.tbtn2').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.tbtn2').forEach(b => b.classList.remove('act'));
      document.querySelectorAll('.tc').forEach(t => t.classList.remove('act'));
      this.classList.add('act');
      const tab = this.dataset.tab === 'health' ? 'tHealth' : 'tTour';
      document.getElementById(tab).classList.add('act');
    });
  });

  /* ═══════════════════════════════════════════════
     VACCINATION TOGGLE
  ═══════════════════════════════════════════════ */
  document.querySelectorAll('#vaxT .vb').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('#vaxT .vb').forEach(b => b.classList.remove('act'));
      this.classList.add('act');
      document.getElementById('vaxIn').value = this.dataset.val;
    });
  });

  /* ═══════════════════════════════════════════════
     LANGUAGE TOGGLE
  ═══════════════════════════════════════════════ */
  document.querySelectorAll('#langT .lb').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('#langT .lb').forEach(b => b.classList.remove('act'));
      this.classList.add('act');
      document.getElementById('langIn').value = this.dataset.lang;
    });
  });

  /* ═══════════════════════════════════════════════
     MENTAL HEALTH SCALE
  ═══════════════════════════════════════════════ */
  document.querySelectorAll('#mentalScale .scale-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const val = +this.dataset.v;
      document.getElementById('mentalIn').value = val;
      document.querySelectorAll('#mentalScale .scale-btn').forEach((b, i) => {
        b.classList.toggle('act', i < val);
      });
    });
  });

  /* ═══════════════════════════════════════════════
     STAR RATINGS (all .sr groups)
  ═══════════════════════════════════════════════ */
  document.querySelectorAll('.sr').forEach(sr => {
    const stars   = sr.querySelectorAll('.st');
    const lbl     = sr.querySelector('.slbl-star');
    const hidden  = sr.nextElementSibling;
    const labels  = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    stars.forEach((star, i) => {
      star.addEventListener('mouseover', () => {
        stars.forEach((s, j) => s.classList.toggle('hov', j <= i));
        if (lbl) lbl.textContent = labels[i + 1];
      });
      star.addEventListener('mouseout', () => {
        const cur = hidden ? +hidden.value : 0;
        stars.forEach((s, j) => {
          s.classList.remove('hov');
          s.classList.toggle('act', j < cur);
        });
        if (lbl) lbl.textContent = cur ? labels[cur] : 'Not yet rated';
      });
      star.addEventListener('click', () => {
        const val = i + 1;
        if (hidden) hidden.value = val;
        stars.forEach((s, j) => s.classList.toggle('act', j < val));
        if (lbl) lbl.textContent = labels[val];
      });
    });
  });

  /* ═══════════════════════════════════════════════
     PROGRESS BAR
  ═══════════════════════════════════════════════ */
  function updateProgress(formId, barId) {
    const form  = document.getElementById(formId);
    const bar   = document.getElementById(barId);
    if (!form || !bar) return;
    const inputs = form.querySelectorAll('input:not([type=hidden]):not([type=radio]):not([type=checkbox]), select, textarea');
    const radios = {};
    form.querySelectorAll('input[type=radio]').forEach(r => { radios[r.name] = radios[r.name] || r.checked; });
    let filled = [...inputs].filter(i => i.value.trim()).length
               + Object.values(radios).filter(Boolean).length;
    let total  = inputs.length + Object.keys(radios).length;
    bar.style.width = Math.min(100, Math.round((filled / (total || 1)) * 100)) + '%';
  }
  ['change', 'input'].forEach(ev => {
    document.getElementById('hForm').addEventListener(ev, () => updateProgress('hForm', 'hProg'));
    document.getElementById('tForm').addEventListener(ev, () => updateProgress('tForm', 'tProg'));
  });

  /* ═══════════════════════════════════════════════
     SUCCESS MODAL (closed by user — no form logic)
  ═══════════════════════════════════════════════ */
  document.getElementById('mClose').addEventListener('click', () => {
    document.getElementById('modal').classList.remove('open');
    if (window.history.replaceState) {
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  });

  /* ═══════════════════════════════════════════════
     THEME TOGGLE
  ═══════════════════════════════════════════════ */
  const root = document.documentElement;
  function toggleTheme() {
    root.setAttribute('data-theme', root.dataset.theme === 'dark' ? 'light' : 'dark');
  }
  ['hTheme','lTheme','rTheme','aTheme'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', toggleTheme);
  });

  /* ═══════════════════════════════════════════════
     ON PAGE LOAD — read URL params from PHP redirects
  ═══════════════════════════════════════════════ */
  (function initFromURL() {
    const params = new URLSearchParams(window.location.search);
    const status = params.get('status');
    const name   = params.get('name') || '';
    const id     = params.get('id')   || '';
    const msg    = params.get('msg')  || '';

    // Immediately clean the URL so refreshing always lands on Home page
    if (window.history.replaceState) {
      window.history.replaceState({}, document.title, window.location.pathname);
    }

    if (status === 'login_success' || status === 'registered') {
      // Save session, go to app (but URL is now clean so refreshes go home)
      setUserSession({ name: name, id: id });
      applyUserUI(name);
      showPage('pgApp');

    } else if (status === 'login_error') {
      clearUserSession();
      const errBox = document.getElementById('loginErr');
      const errMsg = document.getElementById('loginErrMsg');
      if (errBox) errBox.classList.add('show');
      if (errMsg) errMsg.textContent = msg || 'Invalid email or password.';
      showPage('pgLogin');
      
    } else if (status === 'health_submitted') {
      const sess = getUserSession();
      if (sess) applyUserUI(sess.name);
      showPage('pgApp');
      document.getElementById('mMsg').textContent = 'Your health report has been submitted successfully. Salamat!';
      document.getElementById('modal').classList.add('open');

    } else if (status === 'tourism_submitted') {
      const sess = getUserSession();
      if (sess) applyUserUI(sess.name);
      showPage('pgApp');
      document.querySelectorAll('.tbtn2').forEach(b => b.classList.remove('act'));
      document.querySelectorAll('.tc').forEach(t => t.classList.remove('act'));
      document.querySelector('.tbtn2[data-tab="tourism"]').classList.add('act');
      document.getElementById('tTour').classList.add('act');
      document.getElementById('mMsg').textContent = 'Your tourism feedback has been submitted successfully. Maraming salamat!';
      document.getElementById('modal').classList.add('open');

    } else {
      // Normal load (no status param) — Home page stays visible from HTML
      // Just update the avatar if they have a saved session
      const sess = getUserSession();
      if (sess && sess.name) applyUserUI(sess.name);
    }
  })();