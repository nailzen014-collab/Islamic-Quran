/**
 * Shared CSRF-aware fetch helper.
 */
export function request(url, options = {}) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    return fetch(url, {
        ...options,
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
            ...(options.headers || {}),
        },
    });
}

export function pushToast(message, tone = 'info') {
    window.dispatchEvent(new CustomEvent('app:toast', { detail: { message, tone } }));
}