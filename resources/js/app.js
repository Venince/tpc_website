import "./bootstrap";

import Alpine from "alpinejs";
import Collapse from "@alpinejs/collapse";

window.Alpine = Alpine;
Alpine.plugin(Collapse);

/* ---------------------------
   Shared motion / overlay helpers
---------------------------- */
const prefersReducedMotion = () =>
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const smoothBehavior = () => (prefersReducedMotion() ? "auto" : "smooth");
// Inline scripts in the views use this so their programmatic scrolls honour reduced-motion too.
window.tpcScrollBehavior = smoothBehavior;
const emit = (name, detail) =>
    document.dispatchEvent(new CustomEvent(name, { detail }));

// Scroll lock. Lock <html>, never <body>: html has overflow-x:clip, so overflow:hidden on <body>
// makes <body> its own (non-scrolling) scroll container — it un-sticks the header and does NOT stop
// the page behind from scrolling. Keyed, so overlapping overlays / double calls stay balanced.
const scrollLocks = new Set();
function applyScrollLock() {
    const h = document.documentElement;
    if (scrollLocks.size)
        h.style.setProperty("overflow", "hidden", "important");
    else h.style.removeProperty("overflow");
}
window.tpcScrollLock = {
    lock(key = "default") {
        scrollLocks.add(key);
        applyScrollLock();
    },
    unlock(key = "default") {
        scrollLocks.delete(key);
        applyScrollLock();
    },
    clear() {
        scrollLocks.clear();
        applyScrollLock();
    },
};

// Warm the cache for the next/previous lightbox photo so switching never flashes blank.
window.tpcPreload = (src) => {
    if (!src) return;
    const img = new Image();
    img.decoding = "async";
    img.src = src;
};
Alpine.store("gallery", {
    isOpen: false,
    current: 0,
    images: [],
    currentUrl: "",
    init(paths) {
        const raw = typeof paths === "string" ? JSON.parse(paths) : paths;
        this.images = Array.isArray(raw)
            ? raw.map((p) => window._tpcStorageBase + "/" + p)
            : [];
        if (this.images.length) this.currentUrl = this.images[0];
    },
    open(index) {
        this.current = index;
        this.currentUrl = this.images[index];
        this.isOpen = true;
        window.tpcScrollLock.lock("gallery");
        this.warm();
    },
    close() {
        this.isOpen = false;
        window.tpcScrollLock.unlock("gallery");
    },
    prev() {
        this.current =
            (this.current - 1 + this.images.length) % this.images.length;
        this.currentUrl = this.images[this.current];
        this.warm();
    },
    next() {
        this.current = (this.current + 1) % this.images.length;
        this.currentUrl = this.images[this.current];
        this.warm();
    },
    warm() {
        if (this.images.length < 2) return;
        [1, -1].forEach((d) =>
            window.tpcPreload(
                this.images[
                    (this.current + d + this.images.length) % this.images.length
                ],
            ),
        );
    },
});

Alpine.start();

/* ---------------------------
   Helpers
---------------------------- */
const contentEl = () =>
    document.getElementById("tpc-content") ||
    document.getElementById("tpc-admin-main");

const adminMainEl = () => document.getElementById("tpc-admin-main");

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const cssVarNumber = (name, fallback) => {
    const raw = getComputedStyle(document.documentElement)
        .getPropertyValue(name)
        .trim();
    const n = parseFloat(raw);
    return Number.isFinite(n) ? n : fallback;
};

const getDurFor = (container) => {
    if (!container) return 400;
    return container.id === "tpc-admin-main"
        ? cssVarNumber("--tpc-admin-dur", 220)
        : 0;
};

const isSameOrigin = (url) => url.origin === window.location.origin;

const isHomePath = (pathname) => {
    const p = pathname.replace(/\/+$/, "") || "/";
    return p === "/" || p === "";
};

const shouldHandleLink = (a) => {
    if (!a) return false;
    if (a.dataset.noPjax === "true") return false;
    if (a.hasAttribute("download")) return false;
    if (a.target && a.target !== "_self") return false;

    const href = a.getAttribute("href");
    if (!href) return false;
    if (href.startsWith("mailto:") || href.startsWith("tel:")) return false;

    const url = new URL(a.href, window.location.href);
    if (!isSameOrigin(url)) return false;

    return true;
};

function initPageComponents() {
    initLikeButtons();

    // About carousel
    if (
        typeof window.initAboutCarousel === "function" &&
        document.getElementById("about-track")
    ) {
        window.initAboutCarousel();
    }

    // Org chart
    if (typeof window.initOrgChart === "function") {
        window.initOrgChart();
    }

    // Admission sortable — only runs if Sortable is loaded AND the page has lists
    if (typeof window.initAdmissionSortable === "function") {
        window.initAdmissionSortable();
    }

    // Dashboard activity chart — only runs if Chart.js loader ran (defines this
    // globally once) AND the current page actually has the canvas.
    if (
        typeof window.initActivityChart === "function" &&
        document.getElementById("activityChart")
    ) {
        window.initActivityChart();
    }

    // ── Handle post-PJAX anchor scroll (from search navigation) ──
    var anchor = sessionStorage.getItem("tpc_scroll_to");
    if (anchor) {
        sessionStorage.removeItem("tpc_scroll_to");
        window.scrollTo({ top: 0, behavior: "instant" });
        setTimeout(function () {
            if (typeof tpcScrollToAnchor === "function")
                tpcScrollToAnchor(anchor);
        }, 500);
    }

    // ── Sync mobile search panel top to actual header height ──
    var header = document.querySelector("header");
    var panel = document.querySelector("[data-mob-search-panel]");
    if (header && panel) panel.style.top = header.offsetHeight + "px";
}

/* ---------------------------
   Unread badge refresher
---------------------------- */
let unreadBadgeCooldownUntil = 0;
let badgePollTimer = null;

function updateUnreadEverywhere(count) {
    const badge = document.getElementById("nav-messages-badge");
    if (badge) {
        if (count <= 0) {
            badge.classList.add("hidden");
            badge.classList.remove("tpc-badge-pulse");
            badge.textContent = "";
        } else {
            badge.classList.remove("hidden");
            badge.classList.add("tpc-badge-pulse");
            badge.textContent = count > 99 ? "99+" : String(count);
        }
    }

    const inboxCount = document.getElementById("admin-unread-count");
    if (inboxCount) inboxCount.textContent = String(count);
}

async function refreshUnreadBadge({ force = false } = {}) {
    const badge = document.getElementById("nav-messages-badge");
    if (!badge) return;

    const now = Date.now();
    if (!force && now < unreadBadgeCooldownUntil) return;
    unreadBadgeCooldownUntil = now + 800;

    try {
        const res = await fetch(`/tpc_admin/messages/unread-count?ts=${now}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
            credentials: "same-origin",
            cache: "no-store",
        });
        if (!res.ok) return;

        const data = await res.json();
        const count = Number(data.count || 0);
        updateUnreadEverywhere(count);
    } catch (_) {}
}

function startBadgePolling() {
    if (badgePollTimer) return;
    const badge = document.getElementById("nav-messages-badge");
    if (!badge) return;

    badgePollTimer = setInterval(() => refreshUnreadBadge(), 10000);

    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === "visible")
            refreshUnreadBadge({ force: true });
    });
}

/* ---------------------------
   Mobile nav active state
---------------------------- */
const MOBILE_IDS = [
    "mob-home",
    "mob-about",
    "mob-academics",
    "mob-admission",
    "mob-news",
    "mob-contact",
    "mob-messages",
    "mob-services",
];

const MOBILE_LINK_ACTIVE = ["bg-green-50", "text-tpc-secondary"];
const MOBILE_LINK_INACTIVE = ["text-gray-700", "hover:bg-gray-50"];

const MOBILE_ICON_ACTIVE = ["bg-white/20", "text-white"];
const MOBILE_ICON_INACTIVE = ["bg-gray-100", "text-gray-500"];

function setMobileNavActive(activeId) {
    MOBILE_IDS.forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;

        const icon = el.querySelector("span.flex-shrink-0");
        const isActive = id === activeId;

        el.classList.remove(...MOBILE_LINK_ACTIVE, ...MOBILE_LINK_INACTIVE);
        el.classList.add(
            ...(isActive ? MOBILE_LINK_ACTIVE : MOBILE_LINK_INACTIVE),
        );

        if (icon) {
            icon.classList.remove(
                ...MOBILE_ICON_ACTIVE,
                ...MOBILE_ICON_INACTIVE,
            );
            icon.classList.add(
                ...(isActive ? MOBILE_ICON_ACTIVE : MOBILE_ICON_INACTIVE),
            );
        }

        if (el.tagName === "A") {
            const dot = el.querySelector("span.rounded-full");
            const chevron = el.querySelector("svg.h-3\\.5");
            if (dot) dot.classList.toggle("hidden", !isActive);
            if (chevron) chevron.classList.toggle("hidden", isActive);
        }
    });

    if (activeId !== "mob-services") {
        const servBtn = document.getElementById("mob-services");
        if (servBtn) {
            const alpineEl = servBtn.closest("[x-data]");
            if (alpineEl && alpineEl._x_dataStack) {
                try {
                    alpineEl._x_dataStack[0].servOpen = false;
                } catch (_) {}
            }
        }
    }
}

/* ---------------------------
   Nav active state
---------------------------- */
function setNavActiveByUrl(urlStr = window.location.href) {
    const url = new URL(urlStr, window.location.origin);
    const path = url.pathname.replace(/\/+$/, "") || "/";

    const links = document.querySelectorAll("[data-tpc-link]");
    links.forEach((l) => l.classList.remove("tpc-active"));

    document
        .querySelectorAll(".tpc-navlink")
        .forEach((l) => l.classList.remove("tpc-active"));

    document.querySelectorAll("span[data-service-dot]").forEach((dot) => {
        dot.style.backgroundColor = "";
        dot.style.opacity = "";
    });

    if (isHomePath(path)) {
        if (url.hash === "#about") {
            document.getElementById("nav-about")?.classList.add("tpc-active");
            setMobileNavActive("mob-about");
        } else {
            document.getElementById("nav-home")?.classList.add("tpc-active");
            setMobileNavActive("mob-home");
        }
        return;
    }

    if (
        path === "/tpc_admin/messages" ||
        path.startsWith("/tpc_admin/messages/")
    ) {
        document.getElementById("nav-messages")?.classList.add("tpc-active");
        setMobileNavActive("mob-messages");
        return;
    }

    if (path === "/tpc_admin" || path.startsWith("/tpc_admin/")) {
        document.getElementById("nav-admin")?.classList.add("tpc-active");
        setMobileNavActive(null);
        return;
    }

    links.forEach((l) => {
        try {
            const lu = new URL(l.href, window.location.origin);
            const lp = lu.pathname.replace(/\/+$/, "") || "/";
            if (lp === path) l.classList.add("tpc-active");
        } catch (_) {}
    });

    if (path.startsWith("/services")) {
        document.querySelectorAll("button.tpc-navlink").forEach((btn) => {
            if (btn.textContent.trim().startsWith("Services")) {
                btn.classList.add("tpc-active");
            }
        });
        setMobileNavActive("mob-services");

        document.querySelectorAll("a[data-service-href]").forEach((a) => {
            const dot = a.querySelector("span[data-service-dot]");
            if (!dot) return;
            const linkPath = new URL(
                a.dataset.serviceHref,
                window.location.origin,
            ).pathname.replace(/\/+$/, "");
            const isActive = linkPath === path;
            dot.style.backgroundColor = isActive
                ? "var(--color-tpc-primary, #16a34a)"
                : "";
            dot.style.opacity = isActive ? "1" : "0.3";
        });

        return;
    }

    if (path.startsWith("/news")) {
        setMobileNavActive("mob-news");
        return;
    }

    const mobileMap = {
        "/academics": "mob-academics",
        "/admission": "mob-admission",
        "/contact": "mob-contact",
    };

    setMobileNavActive(mobileMap[path] ?? null);
}

/* ---------------------------
   Home scroll helpers
---------------------------- */
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: smoothBehavior() });
}

function scrollToAbout() {
    const aboutSection = document.getElementById("about");
    if (aboutSection) {
        aboutSection.scrollIntoView({
            behavior: smoothBehavior(),
            block: "start",
        });
        history.replaceState(history.state, "", "#about");
    }
}

/* ---------------------------
   Home/About smooth-scroll + underline transfer
---------------------------- */
let homeCleanup = () => {};

function initHomeNav() {
    homeCleanup();
    const navHome = document.getElementById("nav-home");
    const navAbout = document.getElementById("nav-about");
    const aboutSection = document.getElementById("about");
    // NOTE: the old version also required an element with id="top". No template has one, so this
    // function always returned early and the Home/About underline never followed the scroll.
    if (!navHome || !navAbout || !aboutSection) {
        homeCleanup = () => {};
        return;
    }
    const controller = new AbortController();
    const { signal } = controller;
    let ignoreObserver = false;
    let current = null;
    const setActive = (which) => {
        if (which === current) return; // scroll fires constantly; only touch the DOM on a real change
        current = which;
        navHome.classList.remove("tpc-active");
        navAbout.classList.remove("tpc-active");
        if (which === "about") {
            navAbout.classList.add("tpc-active");
            setMobileNavActive("mob-about");
        } else {
            navHome.classList.add("tpc-active");
            setMobileNavActive("mob-home");
        }
    };
    const getActiveByScroll = () => {
        const aboutTop = aboutSection.getBoundingClientRect().top;
        return aboutTop <= window.innerHeight * 0.4 ? "about" : "home";
    };
    setActive(
        window.location.hash === "#about" ? "about" : getActiveByScroll(),
    );
    const flashAbout = () => {
        aboutSection.classList.remove("tpc-highlight");
        void aboutSection.offsetWidth;
        aboutSection.classList.add("tpc-highlight");
        setTimeout(() => aboutSection.classList.remove("tpc-highlight"), 900);
    };
    navHome.addEventListener(
        "click",
        (e) => {
            if (!isHomePath(window.location.pathname)) return;
            e.preventDefault();
            setActive("home");
            scrollToTop();
            history.replaceState(
                history.state,
                "",
                window.location.pathname + window.location.search,
            );
            ignoreObserver = true;
            setTimeout(() => {
                ignoreObserver = false;
            }, 1200);
        },
        { signal },
    );
    navAbout.addEventListener(
        "click",
        (e) => {
            if (!isHomePath(window.location.pathname)) return;
            e.preventDefault();
            setActive("about");
            // keep the pre-existing behaviour (the global link handler used to push an entry for #about)
            if (window.location.hash !== "#about")
                history.pushState(
                    {},
                    "",
                    window.location.pathname +
                        window.location.search +
                        "#about",
                );
            scrollToAbout();
            ignoreObserver = true;
            setTimeout(() => {
                ignoreObserver = false;
            }, 1200);
            flashAbout();
        },
        { signal },
    );
    const onScroll = () => {
        if (ignoreObserver) return;
        setActive(getActiveByScroll());
    };
    window.addEventListener("scroll", onScroll, { signal, passive: true });
    homeCleanup = () => {
        controller.abort();
    };
}

/* ---------------------------
   Handle About link from other pages
---------------------------- */
function handleAboutLinkFromOtherPage(e) {
    const a = e.target.closest("a");
    if (!a) return;

    const href = a.getAttribute("href");
    if (!href) return;

    const url = new URL(a.href, window.location.href);
    const onHome = isHomePath(window.location.pathname);

    if (onHome) return;

    if (isHomePath(url.pathname) && url.hash === "#about") {
        e.preventDefault();
        e.stopImmediatePropagation();

        document
            .querySelectorAll("[data-tpc-link]")
            .forEach((l) => l.classList.remove("tpc-active"));
        const navAbout = document.getElementById("nav-about");
        if (navAbout) navAbout.classList.add("tpc-active");
        setMobileNavActive("mob-about");

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
function handleSamePageHash(url, { push = true } = {}) {
    if (!url.hash) return false;
    // Back/forward (push:false) must never add a history entry of its own.
    if (push) history.pushState({}, "", url.href);
    requestAnimationFrame(() => {
        let el = null;
        try {
            el = document.querySelector(url.hash);
        } catch (_) {}
        if (el) {
            el.scrollIntoView({ behavior: smoothBehavior(), block: "start" });
            if (url.hash === "#about") {
                el.classList.remove("tpc-highlight");
                void el.offsetWidth;
                el.classList.add("tpc-highlight");
                setTimeout(() => el.classList.remove("tpc-highlight"), 900);
            }
        }
    });
    setNavActiveByUrl(url.href);
    initHomeNav();
    return true;
}

/* ---------------------------
   PJAX navigation

   Public pages (#tpc-content) and the admin panel (#tpc-admin-main) use separate code paths so the
   public-site fixes cannot change admin behaviour.
---------------------------- */
let ajaxFormBusy = false;

/* ── Public site ─────────────────────────────────────────────────────────── */
let navSeq = 0; // bumped per navigation: a newer one supersedes an older, in-flight one
let navAbort = null;
let leaveEmitted = false;
let displayedKey = window.location.pathname + window.location.search; // page currently on screen
let scrollSaveTimer = null;
const urlKey = (u) => u.pathname + u.search;

// Scroll position lives in the history entry, so Back/Forward can restore it after a PJAX swap
// (history.scrollRestoration is 'manual' on public pages — the browser would otherwise restore
// the position while the OLD page is still on screen, i.e. a visible jump before the fade).
function saveScrollPosition() {
    if (!document.getElementById("tpc-content")) return;
    try {
        history.replaceState(
            { ...(history.state || {}), tpcScroll: window.scrollY },
            "",
        );
    } catch (_) {}
}
function restoreScroll(y) {
    window.scrollTo({ top: y, behavior: "instant" });
    // Lazy images above the target may still be loading: re-apply twice, unless the user moved.
    [150, 600].forEach((ms) =>
        setTimeout(() => {
            const d = Math.abs(window.scrollY - y);
            if (d > 2 && d < 4000)
                window.scrollTo({ top: y, behavior: "instant" });
        }, ms),
    );
}
if (document.getElementById("tpc-content") && "scrollRestoration" in history) {
    history.scrollRestoration = "manual";
    window.addEventListener(
        "scroll",
        () => {
            clearTimeout(scrollSaveTimer);
            scrollSaveTimer = setTimeout(saveScrollPosition, 150);
        },
        { passive: true },
    );
    window.addEventListener("pagehide", saveScrollPosition);
}
function restoreScrollOnBoot() {
    if (!document.getElementById("tpc-content") || window.location.hash) return;
    const y = history.state && history.state.tpcScroll;
    if (typeof y !== "number") return;
    const nav =
        performance.getEntriesByType &&
        performance.getEntriesByType("navigation")[0];
    if (nav && nav.type !== "reload" && nav.type !== "back_forward") return;
    restoreScroll(y);
}

function closeLayoutOverlays() {
    try {
        const g = window.Alpine && window.Alpine.store("gallery");
        if (g && g.isOpen) g.close();
    } catch (_) {}
    window.dispatchEvent(new CustomEvent("close-hamburger"));
}

function hardNavigate(container, url) {
    // Never leave the page faded out / emptied: a bfcache restore would show it blank.
    container.classList.remove("tpc-leave", "tpc-enter", "tpc-enter-home");
    window.location.href = url.href;
}

async function pjaxNavigatePublic(
    url,
    { replace = false, fromPopstate = false } = {},
) {
    const container = document.getElementById("tpc-content");
    if (!container) {
        window.location.href = url.href;
        return;
    }
    clearTimeout(scrollSaveTimer);

    // 1. Moving to another spot of the page that is already on screen: scroll only, no swap.
    if (urlKey(url) === displayedKey && url.hash) {
        handleSamePageHash(url, { push: !fromPopstate });
        return;
    }
    // 1b. Back/forward between entries of the same page (e.g. /#about -> /): just restore scroll.
    if (fromPopstate && urlKey(url) === displayedKey) {
        const y = history.state && history.state.tpcScroll;
        restoreScroll(typeof y === "number" ? y : 0);
        setNavActiveByUrl(url.href);
        initHomeNav();
        return;
    }

    // 2. Start. A newer navigation supersedes one still in flight (the old code silently dropped
    //    clicks and, worse, Back/Forward presses while busy, leaving URL and content out of sync).
    const myNav = ++navSeq;
    const stale = () => myNav !== navSeq;
    if (navAbort) navAbort.abort();
    navAbort = new AbortController();
    if (!fromPopstate) saveScrollPosition();

    if (!leaveEmitted) {
        leaveEmitted = true;
        emit("tpc:page-leave");
    }
    container.classList.remove("tpc-enter", "tpc-enter-home");
    container.classList.add("tpc-leave");
    setNavActiveByUrl(url.href); // underline moves at click time, not ~200ms later

    // 3. Fetch while fading out. The swap waits for the fade-out to finish (the old code swapped at
    //    220ms of a 600ms fade, so the new page appeared at ~15% opacity: a visible flash).
    const leaveMs = prefersReducedMotion()
        ? 0
        : cssVarNumber("--tpc-page-leave", 180);
    let res, html;
    try {
        const fetchPromise = fetch(url.href, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
            credentials: "same-origin",
            signal: navAbort.signal,
        });
        [res] = await Promise.all([fetchPromise, sleep(leaveMs)]);
        if (stale()) return;
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        html = await res.text();
        if (stale()) return;
    } catch (err) {
        if (stale() || (err && err.name === "AbortError")) return;
        hardNavigate(container, url);
        return;
    }

    // 4. Parse + validate BEFORE touching the DOM (old code emptied the page first, so a response
    //    without #tpc-content left a blank page).
    const doc = new DOMParser().parseFromString(html, "text/html");
    const newContainer = doc.getElementById("tpc-content");
    if (!newContainer) {
        hardNavigate(container, url);
        return;
    }

    let finalUrl = url;
    try {
        const r = new URL(res.url);
        if (r.origin === window.location.origin) {
            finalUrl = r;
            finalUrl.hash = url.hash;
        }
    } catch (_) {}

    // 5. Swap — everything from here to the end is synchronous, so the browser never paints a
    //    half-built page.
    emit("tpc:page-swap");
    leaveEmitted = false;
    window.tpcScrollLock.clear();
    closeLayoutOverlays();
    if (window._aboutCarouselTimer) {
        clearInterval(window._aboutCarouselTimer);
        window._aboutCarouselTimer = null;
    }
    window.initAboutCarousel = null;
    window.initOrgChart = null;
    window._ocApply = null;

    const savedY =
        fromPopstate && history.state ? history.state.tpcScroll : undefined;
    const toAbout = !!window._scrollToAboutAfterNav;
    window._scrollToAboutAfterNav = false;

    container.innerHTML = newContainer.innerHTML;
    if (doc.title) document.title = doc.title;
    if (!fromPopstate) {
        if (replace) history.replaceState({}, "", finalUrl.href);
        else history.pushState({}, "", finalUrl.href);
    }
    displayedKey = urlKey(fromPopstate ? url : finalUrl);

    // Scroll while the page is invisible (the old code jumped to the top at click time, with the old
    // page still on screen).
    if (typeof savedY === "number" && !finalUrl.hash && !toAbout)
        restoreScroll(savedY);
    else window.scrollTo({ top: 0, behavior: "instant" });

    // Enter start-state: applied with transition:none, released after the browser has painted it.
    const enterClass = isHomePath(finalUrl.pathname)
        ? "tpc-enter-home"
        : "tpc-enter";
    container.classList.remove("tpc-leave");
    container.classList.add(enterClass);
    void container.offsetHeight;

    container.querySelectorAll("script").forEach((oldScript) => {
        const newScript = document.createElement("script");
        [...oldScript.attributes].forEach((attr) =>
            newScript.setAttribute(attr.name, attr.value),
        );
        newScript.textContent = oldScript.textContent;
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
    try {
        if (window.Alpine) window.Alpine.initTree(container);
    } catch (_) {}

    setNavActiveByUrl(finalUrl.href);
    initHomeNav();
    refreshUnreadBadge({ force: true });
    initPageComponents(); // synchronous: no 60ms window where carousels / org chart / likes show their un-initialised state

    if (toAbout) {
        requestAnimationFrame(() => {
            setTimeout(() => {
                const aboutSection = document.getElementById("about");
                if (aboutSection) {
                    aboutSection.scrollIntoView({
                        behavior: smoothBehavior(),
                        block: "start",
                    });
                    history.replaceState(history.state, "", "#about");
                }
            }, 80);
        });
    } else if (finalUrl.hash) {
        requestAnimationFrame(() => {
            let el = null;
            try {
                el = document.querySelector(finalUrl.hash);
            } catch (_) {}
            if (el)
                el.scrollIntoView({
                    behavior: smoothBehavior(),
                    block: "start",
                });
        });
    } else {
        // Keyboard / screen-reader users: continue from the new page, not from a link that no longer exists.
        container.setAttribute("tabindex", "-1");
        try {
            container.focus({ preventScroll: true });
        } catch (_) {}
    }

    emit("tpc:page-enter");
    requestAnimationFrame(() =>
        requestAnimationFrame(() => {
            if (!stale()) container.classList.remove(enterClass);
        }),
    );
}

/* ── Admin panel: original behaviour, untouched ──────────────────────────── */
let navigating = false;
async function pjaxNavigateAdmin(
    url,
    { replace = false, fromPopstate = false } = {},
) {
    if (navigating) return;
    if (ajaxFormBusy) return;
    navigating = true;
    const container = contentEl();
    if (!container) {
        navigating = false;
        window.location.href = url.href;
        return;
    }
    // Same page hash only
    if (
        url.pathname === window.location.pathname &&
        url.search === window.location.search
    ) {
        if (handleSamePageHash(url)) {
            navigating = false;
            return;
        }
    }
    const dur = getDurFor(container);
    const fetchPromise = fetch(url.href, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        credentials: "same-origin",
    });
    if (!url.hash && !window._scrollToAboutAfterNav) {
        window.scrollTo({ top: 0 });
    }
    let html;
    container.style.minHeight = container.offsetHeight + "px";
    container.classList.add("tpc-admin-leave");
    await sleep(dur);
    try {
        const res = await fetchPromise;
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        html = await res.text();
    } catch (_) {
        container.classList.remove("tpc-admin-leave");
        container.style.minHeight = "";
        navigating = false;
        window.location.href = url.href;
        return;
    }
    container.innerHTML = "";
    const doc = new DOMParser().parseFromString(html, "text/html");
    const newContainer = doc.getElementById(container.id);
    if (!newContainer) {
        navigating = false;
        window.location.href = url.href;
        return;
    }
    const newH1 = doc.querySelector("header h1");
    const curH1 = document.querySelector("header h1");
    if (newH1 && curH1) curH1.textContent = newH1.textContent.trim();
    const newAside = doc.querySelector("aside");
    const curAside = document.querySelector("aside");
    if (newAside && curAside) {
        curAside.innerHTML = newAside.innerHTML;
        try {
            window.Alpine?.initTree(curAside);
        } catch (_) {}
    }
    if (window._aboutCarouselTimer) {
        clearInterval(window._aboutCarouselTimer);
        window._aboutCarouselTimer = null;
    }
    window.initAboutCarousel = null;
    window.initOrgChart = null;
    container.innerHTML = newContainer.innerHTML;
    container.style.minHeight = "";
    container.querySelectorAll("script").forEach((oldScript) => {
        const newScript = document.createElement("script");
        [...oldScript.attributes].forEach((attr) =>
            newScript.setAttribute(attr.name, attr.value),
        );
        newScript.textContent = oldScript.textContent;
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
    if (doc.title) document.title = doc.title;
    if (!fromPopstate) {
        if (replace) history.replaceState({}, "", url.href);
        else history.pushState({}, "", url.href);
    }
    try {
        if (window.Alpine) window.Alpine.initTree(container);
    } catch (_) {}
    if (url.hash) {
        requestAnimationFrame(() => {
            const el = document.querySelector(url.hash);
            if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
            else window.scrollTo({ top: 0 });
        });
    }
    container.classList.remove("tpc-admin-leave");
    setNavActiveByUrl(url.href);
    initHomeNav();
    refreshUnreadBadge({ force: true });
    setTimeout(initPageComponents, 60);
    navigating = false;
}

/* ── Dispatcher (same name and signature as before) ──────────────────────── */
function pjaxNavigate(urlStr, opts = {}) {
    const url = new URL(urlStr, window.location.origin);
    if (ajaxFormBusy) return Promise.resolve();
    if (document.getElementById("tpc-content"))
        return pjaxNavigatePublic(url, opts);
    if (document.getElementById("tpc-admin-main"))
        return pjaxNavigateAdmin(url, opts);
    window.location.href = url.href;
    return Promise.resolve();
}
// Exposed: nav search results and the About carousel call this. It used to be module-scoped, so
// `typeof pjaxNavigate === 'function'` was always false in those inline scripts and they fell back
// to full page reloads (white flash, no transition).
window.pjaxNavigate = pjaxNavigate;

/* ---------------------------
   Public GET forms (news search / filter) navigate through PJAX like links do,
   so they get the same page transition instead of a hard reload.
---------------------------- */
document.addEventListener("submit", (e) => {
    if (e.defaultPrevented) return;
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;
    if (form.method.toLowerCase() !== "get") return;
    if (form.dataset.ajax === "true" || form.dataset.noPjax === "true") return;
    if (!form.closest("#tpc-content")) return;
    if (form.target && form.target !== "_self") return;
    const action = new URL(
        form.getAttribute("action") || window.location.href,
        window.location.href,
    );
    if (action.origin !== window.location.origin) return;
    e.preventDefault();
    action.search = new URLSearchParams(new FormData(form)).toString();
    pjaxNavigate(action.href);
});

/* ---------------------------
   AJAX for Mark Read/Unread
---------------------------- */
document.addEventListener("submit", async (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;
    if (form.dataset.ajax !== "true") return;

    e.preventDefault();
    ajaxFormBusy = true;

    const btn = form.querySelector("button");
    if (btn) {
        btn.disabled = true;
        btn.classList.add("opacity-70");
    }

    try {
        const res = await fetch(form.action, {
            method: "POST",
            body: new FormData(form),
            headers: { "X-Requested-With": "XMLHttpRequest" },
            credentials: "same-origin",
        });

        if (!res.ok) throw new Error("Request failed");

        const readForm = document.getElementById("mark-read-form");
        const unreadForm = document.getElementById("mark-unread-form");

        if (form.id === "mark-read-form") {
            readForm?.classList.add("hidden");
            unreadForm?.classList.remove("hidden");
        } else if (form.id === "mark-unread-form") {
            unreadForm?.classList.add("hidden");
            readForm?.classList.remove("hidden");
        }

        refreshUnreadBadge({ force: true });
    } catch (_) {
        form.submit();
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove("opacity-70");
        }
        ajaxFormBusy = false;
    }
});

/* ---------------------------
   Like buttons (localStorage persistence)
---------------------------- */
const LIKED_KEY = "tpc_liked_posts";

function getLikedPosts() {
    try {
        return new Set(JSON.parse(localStorage.getItem(LIKED_KEY) || "[]"));
    } catch (_) {
        return new Set();
    }
}

function saveLikedPosts(set) {
    try {
        localStorage.setItem(LIKED_KEY, JSON.stringify([...set]));
    } catch (_) {}
}

function applyLikeState(btn, active) {
    const icon = btn.querySelector(".like-icon");
    if (active) {
        btn.classList.add(
            "border-tpc-primary",
            "text-tpc-primary",
            "bg-tpc-primary/10",
        );
        btn.classList.remove("border-gray-200", "text-gray-500");
        if (icon) {
            icon.setAttribute("fill", "currentColor");
            icon.setAttribute("stroke", "none");
        }
    } else {
        btn.classList.remove(
            "border-tpc-primary",
            "text-tpc-primary",
            "bg-tpc-primary/10",
        );
        btn.classList.add("border-gray-200", "text-gray-500");
        if (icon) {
            icon.setAttribute("fill", "none");
            icon.setAttribute("stroke", "currentColor");
        }
    }
}

function initLikeButtons() {
    const liked = getLikedPosts();
    document.querySelectorAll(".like-btn[data-post-id]").forEach((btn) => {
        applyLikeState(btn, liked.has(String(btn.dataset.postId)));
    });
}

async function handleLike(btn) {
    const id = String(btn.dataset.postId);
    const liked = getLikedPosts();
    const isLiked = liked.has(id);

    const countEl = btn.querySelector(".like-count");
    const labelEl = btn.querySelector(".like-label");
    const current = parseInt(
        countEl?.textContent || btn.dataset.likes || "0",
        10,
    );

    // Optimistic UI update
    if (isLiked) {
        liked.delete(id);
        applyLikeState(btn, false);
        if (countEl) countEl.textContent = Math.max(0, current - 1);
        if (labelEl) labelEl.textContent = "Like";
    } else {
        liked.add(id);
        applyLikeState(btn, true);
        if (countEl) countEl.textContent = current + 1;
        if (labelEl) labelEl.textContent = "Liked";
    }
    saveLikedPosts(liked);

    // Sync with server
    try {
        const endpoint = isLiked ? "unlike" : "like";
        const res = await fetch(`/news/${id}/${endpoint}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content ?? "",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });
        if (res.ok) {
            const data = await res.json();
            if (countEl) countEl.textContent = data.likes_count;
        }
    } catch (_) {
        // Network failure — optimistic state stays
    }
}

window.handleLike = handleLike;

/* ---------------------------
   Boot
---------------------------- */
window.addEventListener("DOMContentLoaded", () => {
    restoreScrollOnBoot();
    setNavActiveByUrl(window.location.href);
    initHomeNav();
    refreshUnreadBadge({ force: true });
    startBadgePolling();
    initPageComponents();

    if (typeof window.initAdmissionSortable === "function") {
        window.initAdmissionSortable();
    }

    initLikeButtons();
});

// Click intercept
document.addEventListener("click", (e) => {
    if (e.defaultPrevented) return;
    if (e.button !== 0) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

    const a = e.target.closest("a");
    if (!a) return;

    const url = new URL(a.href, window.location.href);

    if (
        isHomePath(url.pathname) &&
        url.hash === "#about" &&
        !isHomePath(window.location.pathname)
    ) {
        e.preventDefault();

        document
            .querySelectorAll("[data-tpc-link]")
            .forEach((l) => l.classList.remove("tpc-active"));
        const navAbout = document.getElementById("nav-about");
        if (navAbout) navAbout.classList.add("tpc-active");
        setMobileNavActive("mob-about");

        window._scrollToAboutAfterNav = true;
        pjaxNavigate(url.href);
        return;
    }

    if (!shouldHandleLink(a)) return;

    e.preventDefault();
    pjaxNavigate(a.href);
});

// Back/forward
window.addEventListener("popstate", () => {
    clearTimeout(scrollSaveTimer); // a pending save would write the OLD page's scroll into the entry we are returning to
    pjaxNavigate(window.location.href, { fromPopstate: true, replace: true });
    refreshUnreadBadge({ force: true });
});

// Handle bfcache
window.addEventListener("pageshow", (e) => {
    adminMainEl()?.classList.remove("tpc-admin-leave");
    document.documentElement.classList.remove("tpc-admin-init");
    // Restored from the back/forward cache: never show a page frozen mid-transition (blank, opacity 0).
    const c = document.getElementById("tpc-content");
    if (c) c.classList.remove("tpc-leave", "tpc-enter", "tpc-enter-home");
    if (e.persisted && c) {
        document.documentElement.classList.remove("tpc-home-init");
        window.tpcScrollLock.clear();
        leaveEmitted = false;
        emit("tpc:page-restore");
    }
});
