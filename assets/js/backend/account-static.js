const createFormData = (action, data = {}) => {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('nonce', cookiebot_account.nonce);

    Object.entries(data).forEach(([key, value]) => {
        formData.append(key, value);
    });

    return formData;
};

document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ucApiCode = urlParams.get('uc_api_code');

    if (ucApiCode && cookiebot_account.auth_expired_flow) {
        try {
            const response = await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_process_auth_code', { code: ucApiCode }),
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error(`Auth failed: ${response.status}`);
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.delete('uc_api_code');
            newUrl.searchParams.delete('is_new_user');
            window.location.href = newUrl;
            return;
        } catch (error) {
            console.error('Failed to process authentication:', error);
            return;
        }
    }
    window.prevent_default = true;
});

document.getElementById('get-started-button-static-dashboard')?.addEventListener('click', async (e) => {
    e.preventDefault();
    try {
        const callbackUrl = window.location.protocol + '//' + window.location.hostname + '/wp-admin/admin.php?page=cookiebot';
        window.location.href = `https://api.ea.prod.usercentrics.cloud/v1/auth/auth0/authorize?origin=wordpress_plugin&callback_domain=${encodeURIComponent(callbackUrl)}`;
    } catch (error) {
        console.error('Failed to start authentication process:', error);
    }
});