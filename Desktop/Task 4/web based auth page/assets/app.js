document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-toggle-password]').forEach((toggle) => {
    const rawSelectors = toggle.getAttribute('data-toggle-password') || '';
    const inputs = rawSelectors
      .split(',')
      .map((selector) => selector.trim())
      .filter(Boolean)
      .map((selector) => document.querySelector(selector))
      .filter(Boolean);

    const updateVisibility = () => {
      inputs.forEach((input) => {
        if (input instanceof HTMLInputElement) {
          input.type = toggle.checked ? 'text' : 'password';
        }
      });
    };

    toggle.addEventListener('change', updateVisibility);
    updateVisibility();
  });

  const password = document.querySelector('[data-password]');
  const confirmPassword = document.querySelector('[data-confirm-password]');
  const status = document.querySelector('[data-password-status]');

  if (password instanceof HTMLInputElement && confirmPassword instanceof HTMLInputElement) {
    const validate = () => {
      if (!confirmPassword.value) {
        confirmPassword.setCustomValidity('');
        if (status) {
          status.textContent = '';
          status.classList.remove('is-error', 'is-ok');
        }
        return;
      }

      if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Passwords do not match.');
        if (status) {
          status.textContent = 'Passwords do not match.';
          status.classList.remove('is-ok');
          status.classList.add('is-error');
        }
      } else {
        confirmPassword.setCustomValidity('');
        if (status) {
          status.textContent = 'Passwords match.';
          status.classList.remove('is-error');
          status.classList.add('is-ok');
        }
      }
    };

    password.addEventListener('input', validate);
    confirmPassword.addEventListener('input', validate);
    validate();
  }
});
