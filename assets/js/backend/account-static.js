
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ucApiCode = urlParams.get('uc_api_code');

    if (ucApiCode) {
        try {
            const response = await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_process_auth_code', { code: ucApiCode }),
                credentials: 'same-origin'
            });

            authToken = await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_get_auth_token'),
                credentials: 'same-origin'
            }).then(r => r.json()).then(data => data.data);

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
        // If multisite is enabled the url might include also the directory for the site
        // e.g.: http://domain/site1/wp-admin/admin.php?page=cookiebot
        const callbackUrl = window.location.href.substring(0, window.location.href.indexOf('/wp-admin')) + '/wp-admin/admin.php?page=cookiebot';
        window.location.href = `${API_BASE_URL}/auth/auth0/authorize?origin=wordpress_plugin&callback_domain=${encodeURIComponent(callbackUrl)}`;
    } catch (error) {
        console.error('Failed to start authentication process:', error);
    }
});