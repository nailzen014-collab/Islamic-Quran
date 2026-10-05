const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Sticky header state + the thin scroll progress bar.
 */
export function initHeader() {
    const header = document.querySelector('[data-header]');
    const progress = document.querySelector('[data-scroll-progress]');
    const toTop = document.querySelector('[data-to-top]');

    let ticking = false;

    const update = () => {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const ratio = scrollable > 0 ? Math.min(scrollTop / scrollable, 1) : 0;

        if (progress) {
            progress.style.setProperty('--scroll', ratio.toFixed(4));
        }

        if (header) {
            header.classList.toggle('is-scrolled', scrollTop > 12);
        }

        if (toTop) {
            toTop.classList.toggle('is-visible', scrollTop > 600);
        }

        ticking = false;
    };

    window.addEventListener(
        'scroll',
        () => {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(update);
        },
        { passive: true },
    );

    update();

    if (toTop) {
        toTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: REDUCED ? 'auto' : 'smooth' });
        });
    }
}