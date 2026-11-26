export function createWizard(steps, initial = steps[0]) {
    const listeners = new Map();
    let current = initial;

    const emit = (step, payload) => {
        (listeners.get(step) || []).forEach((callback) => callback(payload, wizard));
        (listeners.get('*') || []).forEach((callback) => callback({ step, payload }, wizard));
    };

    const wizard = {
        get current() {
            return current;
        },
        steps: [...steps],
        is(step) {
            return current === step;
        },
        on(step, callback) {
            listeners.set(step, [...(listeners.get(step) || []), callback]);
            return wizard;
        },
        go(step, payload) {
            if (!steps.includes(step)) return wizard;
            current = step;
            emit(step, payload);
            return wizard;
        },
        next(payload) {
            const index = steps.indexOf(current);
            if (index > -1 && index < steps.length - 1) {
                wizard.go(steps[index + 1], payload);
            }
            return wizard;
        },
        previous(payload) {
            const index = steps.indexOf(current);
            if (index > 0) {
                wizard.go(steps[index - 1], payload);
            }
            return wizard;
        },
        reset() {
            current = steps[0];
            return wizard;
        },
    };

    return wizard;
}
