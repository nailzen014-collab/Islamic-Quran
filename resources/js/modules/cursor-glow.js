const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Soft light that trails the pointer across the whole page.
 */
export function initCursorGlow({ prefersReducedMotion = REDUCED }) {
    const glow = document.querySelector('[data-cursor-glow]');

    if (!glow || prefersReducedMotion || window.matchMedia('(hover: none)').matches) {
        glow?.remove();
        return;
    }

    let x = window.innerWidth / 2;
    let y = window.innerHeight / 2;
    let currentX = x;
    let currentY = y;

    window.addEventListener('pointermove', (event) => {
        x = event.clientX;
        y = event.clientY;
        glow.classList.add('is-active');
    });

    const tick = () => {
        currentX += (x - currentX) * 0.12;
        currentY += (y - currentY) * 0.12;

        glow.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;
        requestAnimationFrame(tick);
    };

    requestAnimationFrame(tick);
}