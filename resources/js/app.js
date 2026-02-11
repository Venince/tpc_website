import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/* ---------------------------
   Helpers
---------------------------- */
const contentEl = () =>
  document.getElementById('tpc-content') ||
  document.getElementById('tpc-admin-main');

const adminMainEl = () => document.getElementById('tpc-admin-main');

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const cssVarNumber = (name, fallback) => {
  const raw = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
  const n = parseFloat(raw);
  return Number.isFinite(n) ? n : fallback;
};

const getDurFor = (container) => {
  if (!container) return 400;
  return container.id === 'tpc-admin-main'
    ? cssVarNumber('--tpc-admin-dur', 220)
    : cssVarNumber('--tpc-page-dur', 650);
};

const isSameOrigin = (url) => url.origin === window.location.origin;

const shouldHandleLink = (a) => {
  if (!a) return false;

  if (a.dataset.noPjax === 'true') return false;
  if (a.hasAttribute('download')) return false;
  if (a.target && a.target !== '_self') return false;

  const href = a.getAttribute('href');
  if (!href) return false;

  if (href.startsWith('#')) return false;
  if (href.startsWith('mailto:') || href.startsWith('tel:')) return false;

  const url = new URL(a.href, window.location.href);
  if (!isSameOrigin(url)) return false;

  // ✅ We now allow PJAX for ADMIN pages too (so only #tpc-admin-main swaps)
  // but keep your explicit opt-outs via data-no-pjax="true".
  return true;
};

/* ---------------------------
   Unread badge refresher
---------------------------- */
let unreadBadgeCooldownUntil = 0;
let badgePollTimer = null;

function updateUnreadEverywhere(count) {
  const badge = document.getElementById('nav-messages-badge');
  if (badge) {
    if (count <= 0) {
      badge.classList.add('hidden');
      badge.classList.remove('tpc-badge-pulse');
      badge.textContent = '';
    } else {
      badge.classList.remove('hidden');
      badge.classList.add('tpc-badge-pulse');
      badge.textContent = count > 99 ? '99+' : String(count);
    }
  }

  const inboxCount = document.getElementById('admin-unread-count');
  if (inboxCount) inboxCount.textContent = String(count);
}

async function refreshUnreadBadge({ force = false } = {}) {
  const badge = document.getElementById('nav-messages-badge');
  if (!badge) return;

  const now = Date.now();
  if (!force && now < unreadBadgeCooldownUntil) return;
  unreadBadgeCooldownUntil = now + 800;

  try {
    const res = await fetch(`/admin/messages/unread-count?ts=${now}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
      cache: 'no-store',
    });
    if (!res.ok) return;

    const data = await res.json();
    const count = Number(data.count || 0);
    updateUnreadEverywhere(count);
  } catch (_) {}
}

function startBadgePolling() {
  if (badgePollTimer) return;
  const badge = document.getElementById('nav-messages-badge');
  if (!badge) return;

  badgePollTimer = setInterval(() => refreshUnreadBadge(), 10000);

  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') refreshUnreadBadge({ force: true });
  });
}

/* ---------------------------
   Nav active state (public navbar only)
---------------------------- */
function setNavActiveByUrl(urlStr = window.location.href) {
  const url = new URL(urlStr, window.location.origin);
  const path = url.pathname.replace(/\/+$/, '') || '/';

  const links = document.querySelectorAll('[data-tpc-link]');
  links.forEach((l) => l.classList.remove('tpc-active'));

  if (path === '/') {
    const home = document.getElementById('nav-home');
    if (home) home.classList.add('tpc-active');
    return;
  }

  if (path === '/admin/messages' || path.startsWith('/admin/messages/')) {
    const msg = document.getElementById('nav-messages');
    if (msg) msg.classList.add('tpc-active');
    return;
  }

  if (path === '/admin' || path.startsWith('/admin/')) {
    const admin = document.getElementById('nav-admin');
    if (admin) admin.classList.add('tpc-active');
    return;
  }

  links.forEach((l) => {
    try {
      const lu = new URL(l.href, window.location.origin);
      const lp = lu.pathname.replace(/\/+$/, '') || '/';
      if (lp === path) l.classList.add('tpc-active');
    } catch (_) {}
  });
}

/* ---------------------------
   Home/About smooth-scroll + underline transfer
---------------------------- */
let homeCleanup = () => {};

function initHomeNav() {
  homeCleanup();

  const navHome = document.getElementById('nav-home');
  const navAbout = document.getElementById('nav-about');
  const topSection = document.getElementById('top');
  const aboutSection = document.getElementById('about');

  if (!navHome || !navAbout || !topSection || !aboutSection) {
    homeCleanup = () => {};
    return;
  }

  const controller = new AbortController();
  const { signal } = controller;

  const setActive = (which) => {
    navHome.classList.remove('tpc-active');
    navAbout.classList.remove('tpc-active');
    if (which === 'about') navAbout.classList.add('tpc-active');
    else navHome.classList.add('tpc-active');
  };

  setActive(window.location.hash === '#about' ? 'about' : 'home');

  navHome.addEventListener(
    'click',
    (e) => {
      const isHome = window.location.pathname === '/' || window.location.pathname === '';
      if (!isHome) return;

      e.preventDefault();
      setActive('home');
      topSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      history.replaceState(null, '', window.location.pathname + window.location.search);
    },
    { signal }
  );

  navAbout.addEventListener(
    'click',
    (e) => {
      const isHome = window.location.pathname === '/' || window.location.pathname === '';
      if (!isHome) return;

      e.preventDefault();
      setActive('about');
      aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      history.replaceState(null, '', '#about');

      aboutSection.classList.remove('tpc-highlight');
      void aboutSection.offsetWidth;
      aboutSection.classList.add('tpc-highlight');
      setTimeout(() => aboutSection.classList.remove('tpc-highlight'), 900);
    },
    { signal }
  );

  const observer = new IntersectionObserver(
    (entries) => {
      const visible = entries
        .filter((x) => x.isIntersecting)
        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

      if (!visible) return;
      if (visible.target.id === 'about') setActive('about');
      if (visible.target.id === 'top') setActive('home');
    },
    { threshold: [0.35, 0.55, 0.75], rootMargin: '-25% 0px -55% 0px' }
  );

  observer.observe(topSection);
  observer.observe(aboutSection);

  homeCleanup = () => {
    controller.abort();
    observer.disconnect();
  };
}

/* ---------------------------
   Same-page hash navigation handler
---------------------------- */
function handleSamePageHash(url) {
  if (!url.hash) return false;

  history.pushState({}, '', url.href);

  requestAnimationFrame(() => {
    const el = document.querySelector(url.hash);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });

      if (url.hash === '#about') {
        el.classList.remove('tpc-highlight');
        void el.offsetWidth;
        el.classList.add('tpc-highlight');
        setTimeout(() => el.classList.remove('tpc-highlight'), 900);
      }
    }
  });

  initHomeNav();
  return true;
}

/* ---------------------------
   PJAX navigation
   - Public pages: uses .tpc-leave/.tpc-enter
   - Admin pages: uses .tpc-admin-leave on #tpc-admin-main ONLY
     and updates header + sidebar instantly (no animation)
---------------------------- */
let navigating = false;

async function pjaxNavigate(urlStr, { replace = false, fromPopstate = false } = {}) {
  if (navigating) return;
  navigating = true;

  const container = contentEl();
  if (!container) {
    window.location.href = urlStr;
    return;
  }

  const url = new URL(urlStr, window.location.origin);

  // same page hash only
  if (url.pathname === window.location.pathname && url.search === window.location.search) {
    if (handleSamePageHash(url)) {
      navigating = false;
      return;
    }
  }

  const isAdminMain = container.id === 'tpc-admin-main';
  const dur = getDurFor(container);

  // fade out (ONLY container)
  if (isAdminMain) container.classList.add('tpc-admin-leave');
  else container.classList.add('tpc-leave');

  await sleep(dur);

  let html;
  try {
    const res = await fetch(url.href, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    html = await res.text();
  } catch (_) {
    window.location.href = url.href;
    return;
  }

  const doc = new DOMParser().parseFromString(html, 'text/html');

  // swap the SAME container id (tpc-content or tpc-admin-main)
  const containerId = container.id;
  const newContainer = doc.getElementById(containerId);

  if (!newContainer) {
    window.location.href = url.href;
    return;
  }

  // ✅ ADMIN: update header title + sidebar markup (WITHOUT animating them)
  if (isAdminMain) {
    const newH1 = doc.querySelector('header h1');
    const curH1 = document.querySelector('header h1');
    if (newH1 && curH1) curH1.textContent = newH1.textContent.trim();

    const newAside = doc.querySelector('aside');
    const curAside = document.querySelector('aside');
    if (newAside && curAside) {
      curAside.innerHTML = newAside.innerHTML;
      try { window.Alpine?.initTree(curAside); } catch (_) {}
    }
  }

  // swap main content
  container.innerHTML = newContainer.innerHTML;

  if (doc.title) document.title = doc.title;

  if (!fromPopstate) {
    if (replace) history.replaceState({}, '', url.href);
    else history.pushState({}, '', url.href);
  }

  // re-init alpine for swapped content only
  try {
    if (window.Alpine) window.Alpine.initTree(container);
  } catch (_) {}

  // scroll
  if (url.hash) {
    requestAnimationFrame(() => {
      const el = document.querySelector(url.hash);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      else window.scrollTo({ top: 0 });
    });
  } else {
    window.scrollTo({ top: 0 });
  }

  setNavActiveByUrl(url.href);
  initHomeNav();

  // fade in
  if (isAdminMain) {
    container.classList.remove('tpc-admin-leave'); // animates back to normal
  } else {
    container.classList.remove('tpc-leave');
    container.classList.add('tpc-enter');
    requestAnimationFrame(() => container.classList.remove('tpc-enter'));
  }

  refreshUnreadBadge({ force: true });

  navigating = false;
}

/* ---------------------------
   AJAX for Mark Read/Unread (no reload)
---------------------------- */
document.addEventListener('submit', async (e) => {
  const form = e.target;
  if (!(form instanceof HTMLFormElement)) return;
  if (form.dataset.ajax !== 'true') return;

  e.preventDefault();

  const btn = form.querySelector('button');
  if (btn) {
    btn.disabled = true;
    btn.classList.add('opacity-70');
  }

  try {
    const res = await fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });

    if (!res.ok) throw new Error('Request failed');

    const readForm = document.getElementById('mark-read-form');
    const unreadForm = document.getElementById('mark-unread-form');

    if (form.id === 'mark-read-form') {
      readForm?.classList.add('hidden');
      unreadForm?.classList.remove('hidden');
    } else if (form.id === 'mark-unread-form') {
      unreadForm?.classList.add('hidden');
      readForm?.classList.remove('hidden');
    }

    refreshUnreadBadge({ force: true });
  } catch (_) {
    form.submit();
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.classList.remove('opacity-70');
    }
  }
});

/* ---------------------------
   Boot
---------------------------- */
window.addEventListener('DOMContentLoaded', () => {
  requestAnimationFrame(() => {
    document.documentElement.classList.remove('tpc-init');
    document.documentElement.classList.remove('tpc-admin-init');
  });

  setNavActiveByUrl(window.location.href);
  initHomeNav();

  refreshUnreadBadge({ force: true });
  startBadgePolling();
});

// click intercept (PJAX)
document.addEventListener('click', (e) => {
  if (e.defaultPrevented) return;
  if (e.button !== 0) return;
  if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

  const a = e.target.closest('a');
  if (!shouldHandleLink(a)) return;

  e.preventDefault();
  pjaxNavigate(a.href);
});

// back/forward
window.addEventListener('popstate', () => {
  pjaxNavigate(window.location.href, { fromPopstate: true, replace: true });
  refreshUnreadBadge({ force: true });
});

// handle bfcache
window.addEventListener('pageshow', () => {
  adminMainEl()?.classList.remove('tpc-admin-leave');
  document.documentElement.classList.remove('tpc-admin-init');
});
