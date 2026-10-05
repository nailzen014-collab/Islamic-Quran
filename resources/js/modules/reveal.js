const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Scroll-triggered entrance animations.
 *
 * `data-reveal` accepts a direction (`up`, `down`, `left`, `right`, `zoom`,
 * `blur`) and an optional `--reveal-delay` custom property for staggering.
 */
export function initReveals({ gsap, prefersReducedMotion = REDUCED, scope = document }) {
    const elements = scope.querySelectorAll('[data-reveal]:not([data-reveal="none"])');

    if (prefersReducedMotion) {
        elements.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const el = entry.target;

                if (el.dataset.revealBound) return;
                el.dataset.revealBound = 'true';

                const delay = parseFloat(getComputedStyle(el).getPropertyValue('--reveal-delay')) || 0;

                el.classList.add('is-revealed');
                gsap.fromTo(
                    el,
                    { opacity: 0, y: 22, scale: el.dataset.reveal === 'zoom' ? 0.94 : 1 },
                    {
                        opacity: 1,
                        y: 0,
                        scale: 1,
                        duration: 0.85,
                        delay,
                        ease: 'power3.out',
                        clearProps: 'transform,opacity',
                    },
                );

                observer.unobserve(el);
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -6% 0px' },
    );

    elements.forEach((el) => observer.observe(el));
}