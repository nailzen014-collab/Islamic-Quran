import { request, pushToast } from './http';

/**
 * "Save to collection" buttons on the recipe page.
 */
export function initCollectionToggles(scope = document) {
    scope.querySelectorAll('[data-collection-toggle]:not([data-collection-bound])').forEach((button) => {
        button.dataset.collectionBound = 'true';

        button.addEventListener('click', async () => {
            const url = button.dataset.collectionToggle;
            const recipeId = button.dataset.recipe;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            const selected = document.querySelectorAll('[data-collection-option]:checked');
            const collectionId = selected[0]?.value;

            if (!collectionId) {
                pushToast('Pilih satu koleksi dulu.', 'error');
                return;
            }

            button.disabled = true;

            try {
                const response = await request(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ recipe_id: recipeId, _token: csrf }),
                });

                const payload = await response.json();

                if (!response.ok) {
                    pushToast(payload.message || 'Gagal menyimpan.', 'error');
                    return;
                }

                pushToast(payload.message, 'success');

                document.querySelectorAll(`[data-collection-state="${collectionId}"]`).forEach((badge) => {
                    badge.classList.toggle('is-active', payload.in_collection);
                });
            } catch (error) {
                pushToast('Koneksi bermasalah, coba lagi.', 'error');
            } finally {
                button.disabled = false;
            }
        });
    });
}