import { pushToast } from './http';

/**
 * Renders toasts pushed from anywhere in the app.
 */
export function initToasts() {
    let stack = document.querySelector('[data-toast-stack]');

    if (!stack) {
        stack = document.createElement('div');
        stack.className = 'toast-stack';
        stack.setAttribute('data-toast-stack', '');
        stack.setAttribute('role', 'status');
        stack.setAttribute('aria-live', 'polite');
        document.body.appendChild(stack);
    }

    const icons = {
        success: 'M4.5 12.75l6 6 9-13.5',
        error: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
        info: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
    };

    const render = ({ detail }) => {
        const tone = detail.tone || 'info';
        const item = document.createElement('div');
        item.className = `toast toast-${tone}`;

        item.innerHTML = `
            <span class="toast-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${icons[tone] || icons.info}"/>
                </svg>
            </span>
            <p class="toast-message"></p>
        `;
        item.querySelector('.toast-message').textContent = detail.message;

        stack.appendChild(item);
        requestAnimationFrame(() => item.classList.add('is-visible'));

        setTimeout(() => {
            item.classList.remove('is-visible');
            setTimeout(() => item.remove(), 400);
        }, 3600);
    };

    window.addEventListener('app:toast', render);
}

/**
 * Copies text to the clipboard and confirms it with a toast.
 */
export function copyToClipboard(text, label = 'Disalin ke clipboard') {
    if (!text) return;

    const done = () => pushToast(label, 'success');

    if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(text).then(done).catch(() => fallbackCopy(text, done));
        return;
    }

    fallbackCopy(text, done);
}

function fallbackCopy(text, done) {
    const area = document.createElement('textarea');
    area.value = text;
    area.style.position = 'fixed';
    area.style.opacity = '0';
    document.body.appendChild(area);
    area.select();

    try {
        document.execCommand('copy');
        done();
    } catch (error) {
        pushToast('Gagal menyalin.', 'error');
    }

    area.remove();
}