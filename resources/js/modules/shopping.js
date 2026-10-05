import { request, pushToast } from './http';

/**
 * Shopping list: tick items without leaving the page.
 */
export function initShopping(scope = document) {
    scope.querySelectorAll('[data-shopping-toggle]:not([data-shopping-bound])').forEach((button) => {
        button.dataset.shoppingBound = 'true';

        button.addEventListener('click', async () => {
            button.disabled = true;

            try {
                const response = await request(button.dataset.shoppingToggle, { method: 'POST' });
                const payload = await response.json();

                if (!response.ok) {
                    pushToast('Gagal mengubah item.', 'error');
                    return;
                }

                const row = button.closest('[data-shopping-row]');
                row?.classList.toggle('is-done', payload.is_checked);

                document.querySelectorAll('[data-shopping-done]').forEach((el) => (el.textContent = payload.done));
                document.querySelectorAll('[data-shopping-total]').forEach((el) => (el.textContent = payload.total));

                const bar = document.querySelector('[data-shopping-bar]');
                if (bar && payload.total > 0) {
                    bar.style.width = `${Math.round((payload.done / payload.total) * 100)}%`;
                }
            } catch (error) {
                pushToast('Koneksi bermasalah, coba lagi.', 'error');
            } finally {
                button.disabled = false;
            }
        });
    });
}