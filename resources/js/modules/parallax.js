const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Slow drift for media and decorative layers driven by scroll position.
 */
export function initParallax({ gsap, ScrollTrigger, prefersReducedMotion = REDUCED, scope = document }) {
    if (prefersReducedMotion) return;

    scope.querySelectorAll('[data-parallax]:not([data-parallax-bound])').forEach((layer) => {
        layer.dataset.parallaxBound = 'true';

        const depth = parseFloat(layer.dataset.parallax) || 0.1;

        gsap.to(layer, {
            yPercent: depth * 100,
            ease: 'none',
            scrollTrigger: {
                trigger: layer.closest('[data-parallax-scope]') || layer,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
            },
        });
    });

    scope.querySelectorAll('[data-parallax-scope]:not([data-parallax-scope-bound])').forEach((box) => {
        box.dataset.parallaxScopeBound = 'true';

        const track = box.querySelector('[data-parallax-x]');

        if (!track) return;

        gsap.fromTo(
            track,
            { xPercent: -8 },
            {
                xPercent: 8,
                ease: 'none',
                scrollTrigger: { trigger: box, start: 'top bottom', end: 'bottom top', scrub: true },
            },
        );
    });
}