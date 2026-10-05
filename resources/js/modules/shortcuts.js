/**
 * Keyboard affordances: "/" focuses search, "?" opens the help overlay.
 */
export function initSearchFocus() {
    document.addEventListener('keydown', (event) => {
        const tag = document.activeElement?.tagName;

        if (event.key === '/' && tag !== 'INPUT' && tag !== 'TEXTAREA') {
            const input = document.querySelector('input[type="search"]');
            input?.focus();
            event.preventDefault();
        }
    });
}