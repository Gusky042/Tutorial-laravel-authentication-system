// Live "passwords match" indicator for forms that contain [data-password-match].
// The server still validates with the 'confirmed' rule; this only helps the user.
function initPasswordMatch() {
    const indicator = document.querySelector('[data-password-match]');

    if (!indicator) {
        return;
    }

    const password = document.getElementById(indicator.dataset.password);
    const confirmation = document.getElementById(indicator.dataset.confirmation);
    const matchText = indicator.querySelector('[data-match]');
    const mismatchText = indicator.querySelector('[data-mismatch]');

    if (!password || !confirmation || !matchText || !mismatchText) {
        return;
    }

    const update = () => {
        const isEmpty = confirmation.value === '';
        const isMatch = password.value === confirmation.value;

        matchText.classList.toggle('hidden', isEmpty || !isMatch);
        mismatchText.classList.toggle('hidden', isEmpty || isMatch);

        // Stops the browser from submitting while the passwords differ.
        confirmation.setCustomValidity(!isEmpty && !isMatch ? 'Passwords do not match' : '');
    };

    password.addEventListener('input', update);
    confirmation.addEventListener('input', update);
    update();
}

// Show/Hide buttons for password fields: <button data-toggle-password="input-id">.
function initPasswordToggles() {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        const input = document.getElementById(button.dataset.togglePassword);

        if (!input) {
            return;
        }

        const name = button.getAttribute('aria-label').replace(/^Show /, '');

        button.classList.remove('hidden');

        const setVisible = (visible) => {
            input.type = visible ? 'text' : 'password';
            button.textContent = visible ? 'Hide' : 'Show';
            button.setAttribute('aria-pressed', String(visible));
            button.setAttribute('aria-label', (visible ? 'Hide ' : 'Show ') + name);
        };

        button.addEventListener('click', () => setVisible(input.type === 'password'));

        // Always submit as a password field so browsers don't store it as plain text.
        input.form?.addEventListener('submit', () => setVisible(false));
    });
}

function init() {
    initPasswordMatch();
    initPasswordToggles();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
