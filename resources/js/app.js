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

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPasswordMatch);
} else {
    initPasswordMatch();
}
