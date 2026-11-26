import { clearOtp, firstError, jsonHeaders } from './http';

export function initOtpInputs(scope) {
    if (!scope) return;

    const inputs = Array.from(scope.querySelectorAll('.twofa-input'));
    const hidden = scope.querySelector('input[name="code"]');

    if (!inputs.length || !hidden || scope.dataset.otpBound === 'true') {
        return;
    }

    scope.dataset.otpBound = 'true';

    const updateCode = () => {
        hidden.value = inputs.map((input) => input.value).join('');
    };

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 1);

            if (input.value && inputs[index + 1]) {
                inputs[index + 1].focus();
            }

            updateCode();
        });

        input.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && !input.value && inputs[index - 1]) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', (event) => {
            event.preventDefault();

            const pasted = (event.clipboardData?.getData('text') || '')
                .replace(/\D/g, '')
                .slice(0, inputs.length);

            if (!pasted) return;

            pasted.split('').forEach((char, charIndex) => {
                if (inputs[charIndex]) {
                    inputs[charIndex].value = char;
                }
            });

            updateCode();
            inputs[Math.min(pasted.length, inputs.length - 1)]?.focus();
        });
    });

    updateCode();
}

export function initConfirmEnable(config) {
    const form = document.getElementById('confirm2faBtn');
    const modal = document.getElementById('confirmationModel');

    if (!form || form.dataset.bound === 'true') {
        return;
    }

    form.dataset.bound = 'true';

    initOtpInputs(modal);

    const cancel = () => {
        modal?.classList.add('hidden');
        clearOtp(modal);

        if (typeof window.TwoFactorEnable?.close === 'function') {
            window.TwoFactorEnable.close();
        }
    };

    modal?.querySelector('#closeConfirmation')?.addEventListener('click', cancel);
    modal?.querySelector('#cancel')?.addEventListener('click', cancel);

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const code = form.querySelector('input[name="code"]')?.value || '';

        if (code.length !== 6) {
            toastr.error('Enter the 6-digit code from your authenticator app.');
            return;
        }

        const button = form.querySelector('button[type="submit"]');
        if (button) button.disabled = true;

        try {
            const response = await fetch(config.confirmUrl, {
                method: 'POST',
                headers: jsonHeaders(config),
                body: JSON.stringify({ code }),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                toastr.error(firstError(data) || 'Invalid code');
                clearOtp(modal);
                return;
            }

            toastr.success('Two factor enabled successfully!');
            modal?.classList.add('hidden');
            setTimeout(() => window.location.reload(), 1200);
        } catch (error) {
            toastr.error(error.message || 'Something went wrong');
        } finally {
            if (button) button.disabled = false;
        }
    });
}

export function initChallengeForm() {
    const form = document.querySelector('[data-twofactor-challenge]');

    if (!form) return;

    initOtpInputs(form);
}
