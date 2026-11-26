import { initOtpInputs } from './confirm-challenge';
import { clearOtp, firstError, jsonHeaders } from './http';

export function initDisable(config) {
    const trigger = document.querySelector('[data-twofactor-disable]');
    const modal = document.getElementById('disableTwoFactorModel');

    if (!trigger || !modal || trigger.dataset.bound === 'true') {
        return;
    }

    trigger.dataset.bound = 'true';

    const form = document.getElementById('disable2faForm');

    initOtpInputs(modal);

    const open = () => {
        modal.classList.remove('hidden');
        modal.querySelector('.twofa-input')?.focus();
    };

    const close = () => {
        modal.classList.add('hidden');
        clearOtp(modal);
    };

    trigger.addEventListener('click', open);
    modal.querySelector('#closeDisable')?.addEventListener('click', close);
    modal.querySelector('#cancelDisable')?.addEventListener('click', close);

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const code = form.querySelector('input[name="code"]')?.value || '';

        if (code.length !== 6) {
            toastr.error('Enter the 6-digit code from your authenticator app.');
            return;
        }

        const button = form.querySelector('button[type="submit"]');
        if (button) button.disabled = true;

        try {
            const response = await fetch(config.disableUrl, {
                method: 'PUT',
                headers: jsonHeaders(config),
                body: JSON.stringify({ code }),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data.success) {
                toastr.error(firstError(data) || 'Something went wrong');
                clearOtp(modal);
                return;
            }

            toastr.success(data.message || 'Two factor authentication disabled');
            setTimeout(() => window.location.reload(), 1200);
        } catch (error) {
            toastr.error(error.message || 'Something went wrong');
        } finally {
            if (button) button.disabled = false;
        }
    });
}
