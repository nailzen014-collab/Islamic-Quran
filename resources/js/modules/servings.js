/**
 * Serving scaler: multiplies quantities inside ingredient names.
 */
export function initServingScaler() {
    const root = document.querySelector('[data-servings]');

    if (!root) return;

    const base = Number(root.dataset.servings) || 1;
    const display = root.querySelector('[data-servings-display]');
    const minus = root.querySelector('[data-servings-minus]');
    const plus = root.querySelector('[data-servings-plus]');
    const progress = root.querySelector('[data-servings-progress]');
    const total = document.querySelectorAll('[data-ingredient-row]').length;
    let value = base;
    let done = 0;

    const numbers = /(\d+(?:[.,]\d+)?)(\s*(?:\/\s*\d+)?\s*(g|kg|ml|l|cl|pcs|pc|butir|slice|siung|buah|tooth|tablespoon|teaspoon|cup|lb|oz)?)/gi;

    const scale = (text) => {
        const factor = value / base;

        if (factor === 1) return text;

        return text.replace(numbers, (match, amount, unit = '') => {
            const parsed = Number(amount.replace(',', '.'));

            if (Number.isNaN(parsed)) return match;

            const scaled = parsed * factor;
            const formatted = Number.isInteger(scaled) ? String(scaled) : scaled.toFixed(1).replace('.', ',');

            return `${formatted}${unit ? ` ${unit}` : ''}`;
        });
    };

    const paint = () => {
        if (display) display.textContent = value;
        root.style.setProperty('--servings-scale', String(value / base));

        document.querySelectorAll('[data-ingredient-quantity]').forEach((el) => {
            el.textContent = scale(el.dataset.ingredientQuantity);
        });

        document.querySelectorAll('[data-ingredient-item]').forEach((el) => {
            el.classList.toggle('is-done', el.dataset.checked === 'true');
        });

        if (progress) {
            progress.style.width = total > 0 ? `${Math.round((done / total) * 100)}%` : '0%';
        }

        const label = document.querySelector('[data-ingredient-progress-label]');
        if (label) label.textContent = `${done} dari ${total} bahan`;
    };

    const update = (next) => {
        value = Math.min(Math.max(next, 1), 24);
        paint();
    };

    plus?.addEventListener('click', () => update(value + 1));
    minus?.addEventListener('click', () => update(value - 1));

    document.querySelectorAll('[data-ingredient-checkbox]').forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            const row = checkbox.closest('[data-ingredient-row]');
            row.dataset.checked = String(checkbox.checked);
            done = document.querySelectorAll('[data-ingredient-checkbox]:checked').length;
            paint();
        });
    });

    document.querySelectorAll('[data-ingredient-row]').forEach((row) => {
        row.addEventListener('click', (event) => {
            if (event.target.closest('button')) return;
            const checkbox = row.querySelector('[data-ingredient-checkbox]');
            if (checkbox) checkbox.checked = !checkbox.checked;
            checkbox?.dispatchEvent(new Event('change'));
        });
    });

    document.querySelectorAll('[data-toggle-all]').forEach((button) => {
        button.addEventListener('click', () => {
            const boxes = [...document.querySelectorAll('[data-ingredient-checkbox]')];
            const shouldCheck = boxes.some((box) => !box.checked);

            boxes.forEach((box) => {
                box.checked = shouldCheck;
                box.dispatchEvent(new Event('change'));
            });
        });
    });

    paint();
}