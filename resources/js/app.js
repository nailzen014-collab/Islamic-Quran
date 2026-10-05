import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initReveals } from './modules/reveal';
import { initHeader } from './modules/header';
import { initParallax } from './modules/parallax';
import { initSpotlight } from './modules/spotlight';
import { initCounters } from './modules/counters';
import { initCookMode } from './modules/cook-mode';
import { initFavorites } from './modules/favorites';
import { initCollectionToggles } from './modules/collections';
import { initShopping } from './modules/shopping';
import { initToasts } from './modules/toast';
import { initRecipeFilters } from './modules/filters';
import { initInfiniteScroll } from './modules/infinite-scroll';
import { initCursorGlow } from './modules/cursor-glow';
import { initServingScaler } from './modules/servings';
import { initSearchFocus } from './modules/shortcuts';
import { initMarquee } from './modules/marquee';

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * A single toast queue shared by every fetch-driven action.
 */
function themeToggle() {
    const root = document.documentElement;

    return {
        isDark: root.classList.contains('dark'),
        toggleTheme() {
            this.isDark = !this.isDark;
            root.classList.toggle('dark', this.isDark);

            try {
                localStorage.setItem('recipes-theme', this.isDark ? 'dark' : 'light');
            } catch (error) {
                /* storage unavailable, theme stays session-only */
            }
        },
    };
}

function appShell() {
    return {
        mobileOpen: false,
        toggleMobile() {
            this.mobileOpen = !this.mobileOpen;
        },
        closeOverlays() {
            this.mobileOpen = false;
        },
    };
}

Alpine.plugin(collapse);

window.Alpine = Alpine;
window.gsap = gsap;

Alpine.data('themeToggle', themeToggle);
Alpine.data('appShell', appShell);
Alpine.data('toastStack', () => ({ items: [] }));
Alpine.data('filterPanel', () => ({
    open: false,
    init() {
        this.open = Boolean(this.$root.dataset.active);
    },
}));
Alpine.data('ratingInput', () => ({
    value: Number(this.$root.dataset.value || 0),
    hover: 0,
    set(value) {
        this.value = value;
        const input = this.$root.querySelector('input[name="rating"]');
        if (input) input.value = value;
    },
}));

Alpine.start();

function boot() {
    initHeader();
    initToasts();
    initMarquee({ gsap, prefersReducedMotion });
    initCursorGlow({ prefersReducedMotion });
    initSearchFocus();
    initRecipeFilters();
    initInfiniteScroll();

    bindDynamicContent();
    initCookMode({ gsap, prefersReducedMotion });
    initServingScaler();

    document.documentElement.classList.add('is-ready');
}

/**
 * Wire up behaviour for markup that arrives through infinite scroll or filters.
 */
function bindDynamicContent(scope = document) {
    initReveals({ gsap, prefersReducedMotion, scope });
    initParallax({ gsap, ScrollTrigger, prefersReducedMotion, scope });
    initSpotlight({ prefersReducedMotion, scope });
    initCounters({ gsap, prefersReducedMotion, scope });
    initFavorites(scope);
    initCollectionToggles(scope);
    initShopping(scope);
}

document.addEventListener('filter:refresh', (event) => {
    bindDynamicContent(event.target);
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}