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

const isHomePath = (pathname) => {
  const p = pathname.replace(/\/+$/, '') || '/';
  return p === '/' || p === '';
};

const shouldHandleLink = (a) => {
  if (!a) return false;
  if (a.dataset.noPjax === 'true') return false;
  if (a.hasAttribute('download')) return false;
  if (a.target && a.target !== '_self') return false;

  const href = a.getAttribute('href');
  if (!href) return false;
  if (href.startsWith('mailto:') || href.startsWith('tel:')) return false;

  const url = new URL(a.href, window.location.href);
  if (!isSameOrigin(url)) return false;

  return true;
};

/* ---------------------------
   Re-init page-specific components
---------------------------- */
function initPageComponents() {
  if (typeof window.initAboutCarousel === 'function' && document.getElementById('about-track')) {
    window.initAboutCarousel();
  }
}

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
   Nav active state
---------------------------- */
function setNavActiveByUrl(urlStr = window.location.href) {
  const url = new URL(urlStr, window.location.origin);
  const path = url.pathname.replace(/\/+$/, '') || '/';

  const links = document.querySelectorAll('[data-tpc-link]');
  links.forEach((l) => l.classList.remove('tpc-active'));

  // Home page
  if (isHomePath(path)) {
    if (url.hash === '#about') {
      const about = document.getElementById('nav-about');
      if (about) about.classList.add('tpc-active');
    } else {
      const home = document.getElementById('nav-home');
      if (home) home.classList.add('tpc-active');
    }
    return;
  }

  // Admin messages
  if (path === '/admin/messages' || path.startsWith('/admin/messages/')) {
    const msg = document.getElementById('nav-messages');
    if (msg) msg.classList.add('tpc-active');
    return;
  }

  // Admin
  if (path === '/admin' || path.startsWith('/admin/')) {
    const admin = document.getElementById('nav-admin');
    if (admin) admin.classList.add('tpc-active');
    return;
  }

  // All other pages — match by pathname
  links.forEach((l) => {
    try {
      const lu = new URL(l.href, window.location.origin);
      const lp = lu.pathname.replace(/\/+$/, '') || '/';
      if (lp === path) l.classList.add('tpc-active');
    } catch (_) {}
  });
}

/* ---------------------------
   Home scroll helpers
---------------------------- */
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function scrollToAbout() {
  const aboutSection = document.getElementById('about');
  if (aboutSection) {
    aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    history.replaceState(null, '', '#about');
  }
}

/* ---------------------------
   Home/About smooth-scroll + underline transfer
---------------------------- */
let homeCleanup = () => {};

function initHomeNav() {
  homeCleanup();

  const navHome  = document.getElementById('nav-home');
  const navAbout = document.getElementById('nav-about');
  const topSection   = document.getElementById('top');
  const aboutSection = document.getElementById('about');

  // Only wire up scroll behavior when actually on home page
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

  // Set initial active based on hash
  setActive(window.location.hash === '#about' ? 'about' : 'home');

  // Home nav click — scroll to top
  navHome.addEventListener('click', (e) => {
    const onHome = isHomePath(window.location.pathname);
    if (!onHome) return; // let PJAX handle it

    e.preventDefault();
    setActive('home');
    scrollToTop();
    history.replaceState(null, '', window.location.pathname + window.location.search);
  }, { signal });

  // About nav click — scroll to about section
  navAbout.addEventListener('click', (e) => {
    const onHome = isHomePath(window.location.pathname);
    if (!onHome) return; // let PJAX handle it

    e.preventDefault();
    setActive('about');
    scrollToAbout();

    aboutSection.classList.remove('tpc-highlight');
    void aboutSection.offsetWidth;
    aboutSection.classList.add('tpc-highlight');
    setTimeout(() => aboutSection.classList.remove('tpc-highlight'), 900);
  }, { signal });

  // IntersectionObserver to track scroll position
  const observer = new IntersectionObserver(
    (entries) => {
      const visible = entries
        .filter((x) => x.isIntersecting)
        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

      if (!visible) return;
      if (visible.target.id === 'about') setActive('about');
      if (visible.target.id === 'top')   setActive('home');
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
   Handle About link from other pages
   (navigate to /#about via PJAX then scroll)
---------------------------- */
function handleAboutLinkFromOtherPage(e) {
  const a = e.target.closest('a');
  if (!a) return;

  const href = a.getAttribute('href');
  if (!href) return;

  // Check if this is the About nav link pointing to /#about or home#about
  const url = new URL(a.href, window.location.href);
  const onHome = isHomePath(window.location.pathname);

  // If already on home, let initHomeNav handle it
  if (onHome) return;

  // If clicking a link that goes to home + #about from another page
  if (isHomePath(url.pathname) && url.hash === '#about') {
    e.preventDefault();
    e.stopImmediatePropagation();

    // Activate about in nav immediately for visual feedback
    document.querySelectorAll('[data-tpc-link]').forEach(l => l.classList.remove('tpc-active'));
    const navAbout = document.getElementById('nav-about');
    if (navAbout) navAbout.classList.add('tpc-active');

    // PJAX navigate to home, then after swap scroll to about
    window._scrollToAboutAfterNav = true;
    pjaxNavigate(url.href);
  }

  // If clicking a link that goes to home (no hash) from another page — activate Home
  if (isHomePath(url.pathname) && !url.hash) {
    // Let normal PJAX handle, but ensure Home gets active not About
    window._scrollToAboutAfterNav = false;
  }
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

  setNavActiveByUrl(url.href);
  initHomeNav();
  return true;
}

/* ---------------------------
   PJAX navigation
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

  // Same page hash only
  if (url.pathname === window.location.pathname && url.search === window.location.search) {
    if (handleSamePageHash(url)) {
      navigating = false;
      return;
    }
  }

  const isAdminMain = container.id === 'tpc-admin-main';
  const dur = getDurFor(container);

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
  const containerId  = container.id;
  const newContainer = doc.getElementById(containerId);

  if (!newContainer) {
    window.location.href = url.href;
    return;
  }

  if (isAdminMain) {
    const newH1  = doc.querySelector('header h1');
    const curH1  = document.querySelector('header h1');
    if (newH1 && curH1) curH1.textContent = newH1.textContent.trim();

    const newAside = doc.querySelector('aside');
    const curAside = document.querySelector('aside');
    if (newAside && curAside) {
      curAside.innerHTML = newAside.innerHTML;
      try { window.Alpine?.initTree(curAside); } catch (_) {}
    }
  }

  // Clear carousel before swap
  if (window._aboutCarouselTimer) {
    clearInterval(window._aboutCarouselTimer);
    window._aboutCarouselTimer = null;
  }
  window.initAboutCarousel = null;

  // Swap content
  container.innerHTML = newContainer.innerHTML;

  // Re-execute inline scripts
  container.querySelectorAll('script').forEach((oldScript) => {
    const newScript = document.createElement('script');
    [...oldScript.attributes].forEach((attr) => newScript.setAttribute(attr.name, attr.value));
    newScript.textContent = oldScript.textContent;
    oldScript.parentNode.replaceChild(newScript, oldScript);
  });

  if (doc.title) document.title = doc.title;

  if (!fromPopstate) {
    if (replace) history.replaceState({}, '', url.href);
    else history.pushState({}, '', url.href);
  }

  try {
    if (window.Alpine) window.Alpine.initTree(container);
  } catch (_) {}

  // Scroll handling
  if (window._scrollToAboutAfterNav) {
    // Coming from another page, navigating to /#about
    window._scrollToAboutAfterNav = false;
    requestAnimationFrame(() => {
      setTimeout(() => {
        const aboutSection = document.getElementById('about');
        if (aboutSection) {
          aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
          history.replaceState(null, '', '#about');
        }
      }, 80);
    });
  } else if (url.hash) {
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

  if (isAdminMain) {
    container.classList.remove('tpc-admin-leave');
  } else {
    container.classList.remove('tpc-leave');
    container.classList.add('tpc-enter');
    requestAnimationFrame(() => container.classList.remove('tpc-enter'));
  }

  refreshUnreadBadge({ force: true });
  initPageComponents();

  navigating = false;
}

/* ---------------------------
   AJAX for Mark Read/Unread
---------------------------- */
document.addEventListener('submit', async (e) => {
  const form = e.target;
  if (!(form instanceof HTMLFormElement)) return;
  if (form.dataset.ajax !== 'true') return;

  e.preventDefault();

  const btn = form.querySelector('button');
  if (btn) { btn.disabled = true; btn.classList.add('opacity-70'); }

  try {
    const res = await fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });

    if (!res.ok) throw new Error('Request failed');

    const readForm   = document.getElementById('mark-read-form');
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
    if (btn) { btn.disabled = false; btn.classList.remove('opacity-70'); }
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
  initPageComponents();
});

// Click intercept — About link needs special handling from other pages
document.addEventListener('click', (e) => {
  if (e.defaultPrevented) return;
  if (e.button !== 0) return;
  if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

  const a = e.target.closest('a');
  if (!a) return;

  // Special handling for About link from non-home pages
  const href = a.getAttribute('href') || '';
  const url  = new URL(a.href, window.location.href);

  if (isHomePath(url.pathname) && url.hash === '#about' && !isHomePath(window.location.pathname)) {
    e.preventDefault();

    document.querySelectorAll('[data-tpc-link]').forEach(l => l.classList.remove('tpc-active'));
    const navAbout = document.getElementById('nav-about');
    if (navAbout) navAbout.classList.add('tpc-active');

    window._scrollToAboutAfterNav = true;
    pjaxNavigate(url.href);
    return;
  }

  if (!shouldHandleLink(a)) return;

  e.preventDefault();
  pjaxNavigate(a.href);
});

// Back/forward
window.addEventListener('popstate', () => {
  pjaxNavigate(window.location.href, { fromPopstate: true, replace: true });
  refreshUnreadBadge({ force: true });
});

// Handle bfcache
window.addEventListener('pageshow', () => {
  adminMainEl()?.classList.remove('tpc-admin-leave');
  document.documentElement.classList.remove('tpc-admin-init');
});
