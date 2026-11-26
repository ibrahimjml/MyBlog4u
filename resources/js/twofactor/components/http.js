export function jsonHeaders(config) {
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': config.csrf,
    };
}

export function firstError(payload) {
    if (!payload) return '';
    if (typeof payload.message === 'string' && payload.message) return payload.message;

    const errors = payload.errors || {};
    const first = Object.values(errors)[0];

    if (Array.isArray(first)) return first[0] || '';
    if (typeof first === 'string') return first;

    return '';
}

export function clearOtp(scope) {
    if (!scope) return;

    scope.querySelectorAll('.twofa-input').forEach((input) => {
        input.value = '';
    });

    const hidden = scope.querySelector('input[name="code"]');
    if (hidden) hidden.value = '';
}
