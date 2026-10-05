import { request, pushToast } from './http';

/**
 * Heart buttons that toggle a recipe in the current user's favorites.
 */
export function initFavorites(scope = document) {
    scope.querySelectorAll('[data-favorite]:not([data-favorite-bound])').forEach((button) => {
        button.dataset.favoriteBound = 'true';

        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const url = button.dataset.favorite;
            const pressed = button.getAttribute('aria-pressed') === 'true';

            button.disabled = true;

            try {
                const response = await request(url, { method: 'POST' });
                const payload = await response.json();

                if (response.status === 401 && payload.redirect) {
                    window.location.href = payload.redirect;
                    return;
                }

                if (!response.ok) {
                    pushToast(payload.message || 'Gagal menyimpan.', 'error');
                    return;
                }

                const nowPressed = payload.favorited;
                button.setAttribute('aria-pressed', String(nowPressed));
                button.dataset.favoriteLabel = nowPressed ? 'Hapus dari favorit' : 'Simpan ke favorit';

                document.querySelectorAll(`[data-favorite-count="${button.dataset.recipe}"]`).forEach((badge) => {
                    badge.textContent = payload.count;
                });

                if (nowPressed) {
                    burst(button);
                }

                pushToast(payload.message, 'success');
            } catch (error) {
                pushToast('Koneksi bermasalah, coba lagi.', 'error');
            } finally {
                button.disabled = false;
            }
        });
    });
}

function burst(button) {
    const rect = button.getBoundingClientRect();

    for (let i = 0; i < 10; i++) {
        const particle = document.createElement('span');
        particle.className = 'heart-particle';

        const angle = (Math.PI * 2 * i) / 10;
        const distance = 26 + Math.random() * 26;

        particle.style.left = `${rect.left + rect.width / 2}px`;
        particle.style.top = `${rect.top + rect.height / 2}px`;
        particle.style.setProperty('--dx', `${Math.cos(angle) * distance}px`);
        particle.style.setProperty('--dy', `${Math.sin(angle) * distance}px`);

        document.body.appendChild(particle);
        setTimeout(() => particle.remove(), 900);
    }
}