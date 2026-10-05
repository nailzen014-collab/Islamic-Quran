const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Seamless horizontal ticker used for tags and cuisine chips.
 */
export function initMarquee({ gsap, prefersReducedMotion = REDUCED }) {
    const tracks = document.querySelectorAll('[data-marquee]');

    tracks.forEach((track) => {
        const inner = track.querySelector('[data-marquee-track]');

        if (!inner || prefersReducedMotion) return;

        const clone = inner.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        track.appendChild(clone);

        const distance = () => inner.scrollWidth;

        gsap.to(inner, {
            x: () => -distance(),
            duration: () => distance() / 45,
            ease: 'none',
            repeat: -1,
        });

        gsap.to(clone, {
            x: () => -distance(),
            duration: () => distance() / 45,
            ease: 'none',
            repeat: -1,
        });
    });
}