export async function fetchQrCode(config) {
    const response = await fetch(config.enableUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': config.csrf,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    });

    const contentType = response.headers.get('content-type') || '';

    if (contentType.includes('application/json')) {
        const data = await response.json().catch(() => ({}));

        if (!response.ok || data.demo_mode) {
            return { data };
        }

        return { data };
    }

    if (!response.ok) {
        throw new Error('Unable to start two-factor setup.');
    }

    return { html: await response.text() };
}
