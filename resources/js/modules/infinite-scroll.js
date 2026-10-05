/**
 * Appends the next page of results when a sentinel scrolls into view.
 */
export function initInfiniteScroll() {
    const sentinel = document.querySelector('[data-infinite-sentinel]');

    if (!sentinel) return;

    const grid = document.querySelector('[data-filter-grid]');
    const url = sentinel.dataset.infiniteSentinel;
    const fallback = document.querySelector('[data-pagination]');
    let loading = false;

    const loadNext = async () => {
        if (loading) return;
        loading = true;
        sentinel.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const incoming = doc.querySelector('[data-filter-grid]');
            const cards = [...incoming.querySelectorAll('[data-card-entry]')];

            cards.forEach((card) => grid.appendChild(card));

            const nextUrl = doc.querySelector('[data-infinite-sentinel]')?.dataset.infiniteSentinel;

            if (nextUrl) {
                sentinel.dataset.infiniteSentinel = nextUrl;
                grid.dispatchEvent(new CustomEvent('filter:refresh'));
            } else {
                sentinel.remove();
                fallback?.classList.add('is-hidden');
            }

            grid.dispatchEvent(new CustomEvent('filter:refresh'));
        } catch (error) {
            /* keep the manual pagination link as a fallback */
        } finally {
            loading = false;
            sentinel.classList.remove('is-loading');
        }
    };

    const observer = new IntersectionObserver(
        (entries) => {
            if (entries.some((entry) => entry.isIntersecting)) loadNext();
        },
        { rootMargin: '400px 0px' },
    );

    observer.observe(sentinel);
}