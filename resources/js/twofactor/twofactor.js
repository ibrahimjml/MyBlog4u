import { initConfirmEnable, initChallengeForm } from './components/confirm-challenge';
import { initDisable } from './components/disable';
import { initEnable } from './components/enable';
import { initRecoveryCodes } from './components/recovery-codes';
import { initTrustedDevices } from './components/trusted-devices';

const ready = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
    } else {
        callback();
    }
};

const readConfig = (root) => ({
    csrf: root.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '',
    enableUrl: root.dataset.enableUrl || '',
    confirmUrl: root.dataset.confirmUrl || '',
    disableUrl: root.dataset.disableUrl || '',
    regenerateUrl: root.dataset.regenerateUrl || '',
    downloadUrl: root.dataset.downloadUrl || '',
    trustedIndex: root.dataset.trustedIndex || '',
    trustedDestroyAll: root.dataset.trustedDestroyAll || '',
    trustedDestroy: root.dataset.trustedDestroy || '',
});

ready(() => {
    const root = document.querySelector('[data-twofactor]');

    if (root) {
        const config = readConfig(root);

        initEnable(config);
        initConfirmEnable(config);
        initDisable(config);
        initRecoveryCodes(config);
        initTrustedDevices(config);
    }

    initChallengeForm();
});
