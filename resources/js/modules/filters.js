/**
 * Explore filters: live search debounce, chip toggles, and URL state.
 */
export function initRecipeFilters() {
    const form = document.querySelector('[data-filter-form]');

    if (!form) return;

    const results = document.querySelector('[data-filter-results]');
    const countEl = document.querySelector('[data-filter-count]');
    const endpoint = form.dataset.filterForm;

    let controller = null;
    let debounceId = null;

    const syncUrl = () => {
        const params = new URLSearchParams(new FormData(form));

        for (const [key, value] of [...params.entries()]) {
            if (!value) params.delete(key);
        }

        const query = params.toString();
        window.history.replaceState({}, '', query ? `?${query}` : window.location.pathname);
    };

    const load = async (append = false) => {
        controller?.abort();
        controller = new AbortController();

        const url = `${endpoint}?${new FormData(form).toString()}`;
        const grid = results.querySelector('[data-filter-grid]');
        grid?.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal,
            });

            if (!response.ok) throw new Error('request failed');

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const incoming = doc.querySelector('[data-filter-grid]');

            if (incoming) {
                if (append) {
                    incoming.querySelectorAll('[data-card-entry]').forEach((card) => {
                        grid.appendChild(card);
                    });
                } else {
                    grid.innerHTML = incoming.innerHTML;
                }

                const total = doc.querySelector('[data-filter-count]')?.textContent;
                if (total && countEl) countEl.textContent = total;

                const empty = doc.querySelector('[data-filter-empty]');
                document.querySelector('[data-filter-empty]')?.classList.toggle('is-hidden', !empty);

                grid.dispatchEvent(new CustomEvent('filter:refresh'));
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.reload();
            }
        } finally {
            grid?.classList.remove('is-loading');
        }
    };

    form.addEventListener('input', (event) => {
        if (event.target.type === 'search' || event.target.type === 'range') {
            clearTimeout(debounceId);
            debounceId = setTimeout(() => {
                syncUrl();
                load();
            }, 320);
            return;
        }

        syncUrl();
        load();
    });

    form.addEventListener('change', () => {
        syncUrl();
        load();
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        syncUrl();
        load();
    });

    form.addEventListener('reset', () => {
        setTimeout(() => {
            syncUrl();
            load();
        }, 0);
    });

    form.querySelectorAll('[data-filter-clear]').forEach((button) => {
        button.addEventListener('click', () => {
            form.reset();
            syncUrl();
            load();
        });
    });

    window.addEventListener('recipes:filter', () => load());
}