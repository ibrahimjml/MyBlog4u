export function initTrustedDevices(config) {
    const list = document.getElementById('trustedDevicesList');
    const removeAllButton = document.getElementById('removeAllTrustedDevices');

    if (!list || !removeAllButton || list.dataset.bound === 'true') {
        return;
    }

    list.dataset.bound = 'true';

    const requestHeaders = {
        'X-CSRF-TOKEN': config.csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };

    const formatDate = (value) => {
        if (!value) return 'Unknown';
        const date = new Date(value);
        return Number.isNaN(date.getTime()) ? 'Unknown' : date.toLocaleString();
    };

    const deviceIcon = (name = '') => (/iphone|ipad|android/i.test(name) ? 'fa-mobile-screen-button' : 'fa-desktop');

    const emptyState = () => {
        list.innerHTML = '<div class="px-5 py-8 text-center"><span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-secondary text-muted-foreground"><i class="fas fa-laptop"></i></span><p class="mt-3 text-sm font-medium text-foreground">No other trusted devices</p><p class="mt-1 text-xs text-muted-foreground">Devices you trust after signing in will appear here.</p></div>';
        removeAllButton.classList.add('hidden');
    };

    const renderDevices = (devices) => {
        list.replaceChildren();

        if (!devices.length) {
            emptyState();
            return;
        }

        removeAllButton.classList.remove('hidden');

        devices.forEach((device) => {
            const row = document.createElement('div');
            row.className = 'flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between';

            const details = document.createElement('div');
            details.className = 'flex min-w-0 items-start gap-3';
            details.innerHTML = `<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-secondary text-muted-foreground"><i class="fas ${deviceIcon(device.device_name)}"></i></span><div class="min-w-0"><p class="truncate text-sm font-medium text-foreground"></p><p class="hidden mt-0.5 text-xs text-emerald-600">your current device</p><p class="mt-0.5 text-xs text-muted-foreground"></p></div>`;

            details.querySelector('p').textContent = device.device_name || 'Unknown device';

            if (device.isCurrent) {
                details.querySelectorAll('p')[1].classList.remove('hidden');
            }

            details.querySelectorAll('p')[2].textContent = `Last used ${formatDate(device.last_used_at)} - ${device.ip || 'Unknown IP'}`;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'self-start rounded-lg border border-border px-3 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wide text-destructive transition-colors hover:border-destructive sm:self-auto';
            removeButton.textContent = 'Remove';
            removeButton.addEventListener('click', () => removeDevice(device.id, removeButton));

            row.append(details, removeButton);
            list.appendChild(row);
        });
    };

    const loadDevices = async () => {
        try {
            const response = await fetch(config.trustedIndex, { headers: requestHeaders });

            if (!response.ok) throw new Error('Could not load trusted devices.');

            const data = await response.json();
            renderDevices(data.devices || []);
        } catch (error) {
            list.innerHTML = '<div class="px-5 py-5 text-sm text-destructive">Unable to load trusted devices. Please refresh and try again.</div>';
        }
    };

    const removeDevice = async (id, button) => {
        button.disabled = true;
        button.textContent = 'Removing...';

        try {
            const response = await fetch(config.trustedDestroy.replace('__DEVICE_ID__', id), {
                method: 'DELETE',
                headers: requestHeaders,
            });

            if (!response.ok) throw new Error('Could not remove device.');

            toastr.success('Trusted device removed.');
            loadDevices();
        } catch (error) {
            button.disabled = false;
            button.textContent = 'Remove';
            toastr.error('Unable to remove this device.');
        }
    };

    removeAllButton.addEventListener('click', async () => {
        if (!window.confirm('Remove all trusted devices? You will need a two-factor code the next time you sign in on them.')) {
            return;
        }

        removeAllButton.disabled = true;
        removeAllButton.textContent = 'Removing...';

        try {
            const response = await fetch(config.trustedDestroyAll, {
                method: 'DELETE',
                headers: requestHeaders,
            });

            if (!response.ok) throw new Error('Could not remove devices.');

            toastr.success('All trusted devices removed.');
            emptyState();
        } catch (error) {
            toastr.error('Unable to remove trusted devices.');
        } finally {
            removeAllButton.disabled = false;
            removeAllButton.textContent = 'Remove all';
        }
    });

    loadDevices();
}
