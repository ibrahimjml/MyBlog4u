import { firstError, jsonHeaders } from './http';

export function initRecoveryCodes(config) {
    document.querySelectorAll('[data-twofactor-regenerate]').forEach((button) => {
        if (button.dataset.bound === 'true') return;

        button.dataset.bound = 'true';

        button.addEventListener('click', async () => {
            if (!window.confirm('Regenerate recovery codes? Your current codes will stop working.')) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(config.regenerateUrl, {
                    method: 'PUT',
                    headers: jsonHeaders(config),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.success) {
                    toastr.error(firstError(data) || 'Something went wrong');
                    return;
                }

                toastr.success(data.message || 'Recovery codes regenerated');
                setTimeout(() => window.location.reload(), 1200);
            } catch (error) {
                toastr.error(error.message || 'Something went wrong');
            } finally {
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('[data-twofactor-download]').forEach((button) => {
        if (button.dataset.bound === 'true') return;

        button.dataset.bound = 'true';

        button.addEventListener('click', () => {
            window.location.href = config.downloadUrl;
            setTimeout(() => window.location.reload(), 2000);
        });
    });
}
