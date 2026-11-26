import { fetchQrCode } from './fetchqrcode';
import { createWizard } from './wizard';

const DEFAULT_LABEL = '<i class="fas fa-user-shield"></i><small>Enable two-factor</small>';

export function initEnable(config) {
    const openButton = document.getElementById('open2fa');
    const container = document.getElementById('2faContainer');

    if (!openButton || !container || openButton.dataset.bound === 'true') {
        return;
    }

    openButton.dataset.bound = 'true';

    const header = document.getElementById('header');
    const wizard = createWizard(['setup', 'confirm']);
    let enableModal = null;

    const setLoading = (loading) => {
        openButton.disabled = loading;
        openButton.innerHTML = loading ? '<i class="fas fa-spinner fa-spin"></i>' : DEFAULT_LABEL;
    };

    const close = () => {
        enableModal?.classList.add('hidden');
        header?.classList.add('sticky', 'top-0');
        setLoading(false);
        wizard.reset();
    };

    const openConfirm = () => {
        enableModal?.classList.add('hidden');
        document.getElementById('confirmationModel')?.classList.remove('hidden');
        wizard.go('confirm');
    };

    const copySecret = () => {
        const secret = enableModal?.querySelector('#secret')?.innerText?.trim();

        if (!secret) return;

        navigator.clipboard.writeText(secret).then(() => {
            toastr.success('Copied to clipboard');
        });
    };

    const bindModal = () => {
        enableModal = document.getElementById('2faModel');

        if (!enableModal) return;

        enableModal.classList.remove('hidden');
        wizard.go('setup');

        enableModal.querySelector('#continue2fa')?.addEventListener('click', openConfirm);
        enableModal.querySelector('#closeModel')?.addEventListener('click', close);
        enableModal.querySelector('#secret + div')?.addEventListener('click', copySecret);
    };

    openButton.addEventListener('click', async () => {
        setLoading(true);
        header?.classList.remove('sticky', 'top-0');

        try {
            const result = await fetchQrCode(config);

            if (result.data) {
                toastr.error(result.data.message || 'Unable to start two-factor setup.');
                header?.classList.add('sticky', 'top-0');
                return;
            }

            container.innerHTML = result.html;
            bindModal();
        } catch (error) {
            toastr.error(error.message || 'Unable to start two-factor setup.');
            header?.classList.add('sticky', 'top-0');
        } finally {
            setLoading(false);
        }
    });

    window.TwoFactorEnable = { close };
}
