import './bootstrap';

import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(Collapse);
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
    const res = await fetch(`/tpc_admin/messages/unread-count?ts=${now}`, {
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
   Mobile nav active state
---------------------------- */
const MOBILE_IDS = ['mob-home', 'mob-about', 'mob-academics', 'mob-admission', 'mob-news', 'mob-contact', 'mob-messages', 'mob-services'];

const MOBILE_LINK_ACTIVE   = ['bg-tpc-primary', 'text-white', 'shadow-sm'];
const MOBILE_LINK_INACTIVE = ['text-gray-700'];

const MOBILE_ICON_ACTIVE   = ['bg-white/20', 'text-white'];
const MOBILE_ICON_INACTIVE = ['bg-gray-100', 'text-gray-500'];

function setMobileNavActive(activeId) {
  MOBILE_IDS.forEach((id) => {
    const el = document.getElementById(id);
    if (!el) return;

    const icon = el.querySelector('span.flex-shrink-0');
    const isActive = id === activeId;

    el.classList.remove(...MOBILE_LINK_ACTIVE, ...MOBILE_LINK_INACTIVE);
    el.classList.add(...(isActive ? MOBILE_LINK_ACTIVE : MOBILE_LINK_INACTIVE));

    if (icon) {
      icon.classList.remove(...MOBILE_ICON_ACTIVE, ...MOBILE_ICON_INACTIVE);
      icon.classList.add(...(isActive ? MOBILE_ICON_ACTIVE : MOBILE_ICON_INACTIVE));
    }

    if (el.tagName === 'A') {
      const dot     = el.querySelector('span.rounded-full');
      const chevron = el.querySelector('svg.h-3\\.5');
      if (dot)     dot.classList.toggle('hidden', !isActive);
      if (chevron) chevron.classList.toggle('hidden', isActive);
    }
  });

  if (activeId !== 'mob-services') {
    const servBtn = document.getElementById('mob-services');
    if (servBtn) {
      const alpineEl = servBtn.closest('[x-data]');
      if (alpineEl && alpineEl._x_dataStack) {
        try { alpineEl._x_dataStack[0].servOpen = false; } catch (_) {}
      }
    }
  }
}

/* ---------------------------
   Nav active state
---------------------------- */
function setNavActiveByUrl(urlStr = window.location.href) {
  const url  = new URL(urlStr, window.location.origin);
  const path = url.pathname.replace(/\/+$/, '') || '/';

  const links = document.querySelectorAll('[data-tpc-link]');
  links.forEach((l) => l.classList.remove('tpc-active'));

  document.querySelectorAll('.tpc-navlink').forEach((l) => l.classList.remove('tpc-active'));

  document.querySelectorAll('span[data-service-dot]').forEach((dot) => {
    dot.style.backgroundColor = '';
    dot.style.opacity = '';
  });

  if (isHomePath(path)) {
    if (url.hash === '#about') {
      document.getElementById('nav-about')?.classList.add('tpc-active');
      setMobileNavActive('mob-about');
    } else {
      document.getElementById('nav-home')?.classList.add('tpc-active');
      setMobileNavActive('mob-home');
    }
    return;
  }

  if (path === '/tpc_admin/messages' || path.startsWith('/tpc_admin/messages/')) {
    document.getElementById('nav-messages')?.classList.add('tpc-active');
    setMobileNavActive('mob-messages');
    return;
  }

  if (path === '/tpc_admin' || path.startsWith('/tpc_admin/')) {
    document.getElementById('nav-admin')?.classList.add('tpc-active');
    setMobileNavActive(null);
    return;
  }

  links.forEach((l) => {
    try {
      const lu = new URL(l.href, window.location.origin);
      const lp = lu.pathname.replace(/\/+$/, '') || '/';
      if (lp === path) l.classList.add('tpc-active');
    } catch (_) {}
  });

    if (path.startsWith('/services')) {
        document.querySelectorAll('button.tpc-navlink').forEach((btn) => {
            if (btn.textContent.trim().startsWith('Services')) {
                btn.classList.add('tpc-active');
            }
        });
        setMobileNavActive('mob-services');

        // ── NEW: update desktop dropdown dots ──────────────────────
        document.querySelectorAll('a[data-service-href]').forEach((a) => {
            const dot = a.querySelector('span[data-service-dot]');
            if (!dot) return;
            const linkPath = new URL(a.dataset.serviceHref, window.location.origin)
                                .pathname.replace(/\/+$/, '');
            const isActive = linkPath === path;
            dot.style.backgroundColor = isActive
                ? 'var(--color-tpc-primary, #16a34a)'
                : '';
            dot.style.opacity = isActive ? '1' : '0.3';
        });
        // ───────────────────────────────────────────────────────────

        return;
    }

    if (path.startsWith('/news')) {
        setMobileNavActive('mob-news');
        return;
    }

  const mobileMap = {
    '/academics': 'mob-academics',
    '/admission': 'mob-admission',
    '/contact':   'mob-contact',
  };

  setMobileNavActive(mobileMap[path] ?? null);
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

  const navHome      = document.getElementById('nav-home');
  const navAbout     = document.getElementById('nav-about');
  const topSection   = document.getElementById('top');
  const aboutSection = document.getElementById('about');

  if (!navHome || !navAbout || !topSection || !aboutSection) {
    homeCleanup = () => {};
    return;
  }

  const controller = new AbortController();
  const { signal } = controller;

  let ignoreObserver = false;

  const setActive = (which) => {
    navHome.classList.remove('tpc-active');
    navAbout.classList.remove('tpc-active');
    if (which === 'about') {
      navAbout.classList.add('tpc-active');
      setMobileNavActive('mob-about');
    } else {
      navHome.classList.add('tpc-active');
      setMobileNavActive('mob-home');
    }
  };

  // Determine initial active based on scroll position, not just hash
  const getActiveByScroll = () => {
    const aboutTop = aboutSection.getBoundingClientRect().top;
    return aboutTop <= window.innerHeight * 0.4 ? 'about' : 'home';
  };

  setActive(window.location.hash === '#about' ? 'about' : getActiveByScroll());

  navHome.addEventListener('click', (e) => {
    if (!isHomePath(window.location.pathname)) return;
    e.preventDefault();
    setActive('home');
    scrollToTop();
    history.replaceState(null, '', window.location.pathname + window.location.search);
    ignoreObserver = true;
    setTimeout(() => { ignoreObserver = false; }, 1200);
  }, { signal });

  navAbout.addEventListener('click', (e) => {
    if (!isHomePath(window.location.pathname)) return;
    e.preventDefault();
    setActive('about');
    scrollToAbout();
    ignoreObserver = true;
    setTimeout(() => { ignoreObserver = false; }, 1200);
    aboutSection.classList.remove('tpc-highlight');
    void aboutSection.offsetWidth;
    aboutSection.classList.add('tpc-highlight');
    setTimeout(() => aboutSection.classList.remove('tpc-highlight'), 900);
  }, { signal });

  // Use scroll event instead of IntersectionObserver for more reliable Home/About detection
  const onScroll = () => {
    if (ignoreObserver) return;
    setActive(getActiveByScroll());
  };

  window.addEventListener('scroll', onScroll, { signal, passive: true });

  homeCleanup = () => {
    controller.abort();
  };
}

/* ---------------------------
   Handle About link from other pages
---------------------------- */
function handleAboutLinkFromOtherPage(e) {
  const a = e.target.closest('a');
  if (!a) return;

  const href = a.getAttribute('href');
  if (!href) return;

  const url = new URL(a.href, window.location.href);
  const onHome = isHomePath(window.location.pathname);

  if (onHome) return;

  if (isHomePath(url.pathname) && url.hash === '#about') {
    e.preventDefault();
    e.stopImmediatePropagation();

    document.querySelectorAll('[data-tpc-link]').forEach(l => l.classList.remove('tpc-active'));
    const navAbout = document.getElementById('nav-about');
    if (navAbout) navAbout.classList.add('tpc-active');
    setMobileNavActive('mob-about');

    window._scrollToAboutAfterNav = true;
    pjaxNavigate(url.href);
  }

  if (isHomePath(url.pathname) && !url.hash) {
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
let ajaxFormBusy = false;  // prevents PJAX racing against AJAX form submissions

async function pjaxNavigate(urlStr, { replace = false, fromPopstate = false } = {}) {
  if (navigating) return;
  if (ajaxFormBusy) return;  // block PJAX while mark read/unread is in flight
  navigating = true;

  const container = contentEl();
  if (!container) {
    navigating = false;
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
    navigating = false;  // reset before redirect
    window.location.href = url.href;
    return;
  }

  const doc = new DOMParser().parseFromString(html, 'text/html');
  const containerId  = container.id;
  const newContainer = doc.getElementById(containerId);

  if (!newContainer) {
    navigating = false;  // reset before redirect
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
  ajaxFormBusy = true;  // block PJAX during this request

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
    ajaxFormBusy = false;  // unblock PJAX
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

  const url = new URL(a.href, window.location.href);

  if (isHomePath(url.pathname) && url.hash === '#about' && !isHomePath(window.location.pathname)) {
    e.preventDefault();

    document.querySelectorAll('[data-tpc-link]').forEach(l => l.classList.remove('tpc-active'));
    const navAbout = document.getElementById('nav-about');
    if (navAbout) navAbout.classList.add('tpc-active');
    setMobileNavActive('mob-about');

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
