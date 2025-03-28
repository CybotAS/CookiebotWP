const API_BASE_URL = 'https://api.ea.prod.usercentrics.cloud/v1';
let authToken = '';

// Helper function to create FormData for AJAX calls
const createFormData = (action, data = {}) => {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('nonce', cookiebot_account.nonce);

    Object.entries(data).forEach(([key, value]) => {
        formData.append(key, value);
    });

    return formData;
};

// Function to check scan status
async function checkScanStatus(scanId) {
    try {
        const response = await fetch(`${API_BASE_URL}/scan/${scanId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json();

        if (data.status === 'DONE') {
            await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_store_scan_details', {
                    scan_id: scanId,
                    scan_status: 'DONE'
                }),
                credentials: 'same-origin'
            });
            return;
        }

        // Store both scan ID and status
        await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_store_scan_details', {
                scan_id: scanId,
                scan_status: 'IN_PROGRESS'
            }),
            credentials: 'same-origin'
        });

        if (!response.ok) {
            await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_store_scan_details', {
                    scan_id: scanId,
                    scan_status: 'FAILED'
                }),
                credentials: 'same-origin'
            });
        }
    } catch (error) {
        console.error('Error checking scan status:', error);
    }
}

const checkUserData = async () => {
    try {
        const userData = await fetch(`${API_BASE_URL}/accounts`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`,
            }
        }).then(r => r.json()).then(data => data[0]);

        if (!userData) throw new Error('No user data received');

        // Add onboarding flag to user data
        const userDataWithFlag = {
            ...userData,
            onboarded_via_signup: true
        };
        userResponseData = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_post_user_data', {
                data: JSON.stringify(userDataWithFlag)
            }),
            credentials: 'same-origin'
        }).then(r => r.json());

        if (!userResponseData.success) {
            throw new Error('Failed to store user data');
        }
    } catch (error) {
        console.error('Failed to fetch user data:', error);
        return;
    }
}

const isAuthenticated = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/accounts`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            }
        });
        return response.status !== 401;
    } catch (error) {
        console.error('Failed to check authentication:', error);
        return false;
    }
};

// Function to fetch configuration details
async function fetchConfigurationDetails(configId) {
    try {
        const response = await fetch(`${API_BASE_URL}/configurations/${configId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${authToken}`
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch configuration details');
        }

        const data = await response.json();

        // Store full configuration data
        await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_store_configuration', {
                configuration: JSON.stringify(data)
            }),
            credentials: 'same-origin'
        });

        return data;
    } catch (error) {
        console.error('Error fetching configuration details:', error);
        throw error;
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ucApiCode = urlParams.get('uc_api_code');

    // For existing users, no account registration workflow is needed 
    if (cookiebot_account.has_cbid && !cookiebot_account.has_user_data) {
        return;
    }

    try {
        authToken = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_get_auth_token'),
            credentials: 'same-origin'
        }).then(r => r.json()).then(data => data.data);

        if (ucApiCode) {

            const isNewUser = urlParams.get('is_new_user');

            if (cookiebot_account.has_cbid) {
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


            if (isNewUser === 'false' && !cookiebot_account.has_user_data) {
                const settingsUrl = window.location.protocol + '//' + window.location.hostname + '/wp-admin/admin.php?page=cookiebot_settings';
                window.location.href = settingsUrl;
                return;
            }

            // Add loading state
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <h2>Creating your account</h2>
                    <p>This should only take about a minute. Keep this window open while we set things up.</p>
                </div>
            `;
            document.body.classList.add('has-loading-overlay');
            document.body.appendChild(loadingOverlay);

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
            } catch (error) {
                console.error('Failed to process authentication:', error);
                // Remove loading state on error
                document.body.classList.remove('has-loading-overlay');
                loadingOverlay.remove();
                return;
            }
        }

        // Check for existing CBID
        const cbid = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_get_cbid'),
            credentials: 'same-origin'
        }).then(r => r.json()).then(data => data.success ? data.data : null);

        // Skip if user data exists and is authenticated
        const isAuthenticatedCondition = await isAuthenticated();
        if (cookiebot_account.has_user_data && isAuthenticatedCondition) {
            // Check scan status before returning
            await checkScanStatus(cbid);
            await checkUserData();
            return;
        }

        if (!isAuthenticatedCondition && cookiebot_account.has_user_data) {
            const callbackUrl = window.location.protocol + '//' + window.location.hostname + '/wp-admin/admin.php?page=cookiebot';
            window.location.href = `${API_BASE_URL}/auth/auth0/authorize?origin=wordpress_plugin&callback_domain=${encodeURIComponent(callbackUrl)}`;

        }

        if (!cbid) {
            try {
                // Create new configuration
                const siteDomain = window.location.hostname;
                const formattedDomain = siteDomain.startsWith('http') ? siteDomain : `https://${siteDomain}`;

                const configResponse = await fetch(`${API_BASE_URL}/configurations`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({
                        configuration_name: "My CMP Configuration",
                        data_controller: "Usercentrics Unipessoal, LDA",
                        legal_framework_template: "gdpr",
                        domains: [formattedDomain]
                    })
                });

                if (!configResponse.ok) throw new Error(`Config creation failed: ${configResponse.status}`);

                const configData = await configResponse.json();
                if (!configData?.configuration_id) throw new Error('No configuration ID');

                // Store CBID
                await fetch(cookiebot_account.ajax_url, {
                    method: 'POST',
                    body: createFormData('cookiebot_store_cbid', { cbid: configData.configuration_id }),
                    credentials: 'same-origin'
                });

                // Now that we have a CBID, initiate the scan
                const scanResponse = await fetch(`${API_BASE_URL}/scan`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({
                        domains: [formattedDomain],
                        configuration_id: configData.configuration_id
                    })
                });

                if (!scanResponse.ok) throw new Error(`Scan initiation failed: ${scanResponse.status}`);

                const scanData = await scanResponse.json();

                // Check for scan ID in different possible response structures
                const scanId = scanData?.scan?.scan_id || scanData?.scan_id || scanData?.id;
                if (!scanId) {
                    console.error('Scan response structure:', scanData);
                    throw new Error('No scan ID received in response');
                }

                // Store scan ID in WordPress without status initially
                await fetch(cookiebot_account.ajax_url, {
                    method: 'POST',
                    body: createFormData('cookiebot_store_scan_details', {
                        scan_id: scanId
                    }),
                    credentials: 'same-origin'
                });

                // Start checking scan status
                await checkScanStatus(scanId);

                // fetch configuration details 
                await fetchConfigurationDetails(configData.configuration_id);

            } catch (error) {
                console.error('Failed to create configuration:', error);
                return;
            }
        } else {
            // If we already have a CBID, check if there's an ongoing scan
            const scanDetails = await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_get_scan_details'),
                credentials: 'same-origin'
            }).then(r => r.json());

            if (scanDetails.success && scanDetails.data.scan_id) {
                await checkScanStatus(scanDetails.data.scan_id);
            }
            // And fetch configuration details 
            await fetchConfigurationDetails(cbid);
        }

        // Check and fetch user data if needed
        let userResponseData = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_get_user_data'),
            credentials: 'same-origin'
        }).then(r => r.json());

        try {
            const userData = await fetch(`${API_BASE_URL}/accounts`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                }
            }).then(r => r.json()).then(data => data[0]);

            if (!userData) throw new Error('No user data received');

            // Add onboarding flag to user data
            const userDataWithFlag = {
                ...userData,
                onboarded_via_signup: true
            };
            userResponseData = await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_post_user_data', {
                    data: JSON.stringify(userDataWithFlag)
                }),
                credentials: 'same-origin'
            }).then(r => r.json());

            if (!userResponseData.success) {
                throw new Error('Failed to store user data');
            }
        } catch (error) {
            console.error('Failed to fetch user data:', error);
            return;
        }

        if (userResponseData.data) {
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.delete('uc_api_code');
            window.location.href = newUrl;
        }
    } catch (error) {
        console.error('An unexpected error occurred:', error);
    }
});

// Event Listeners
document.getElementById('banner-close-btn')?.addEventListener('click', async () => {
    try {
        const response = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_dismiss_banner'),
            credentials: 'same-origin'
        });

        if (!response.ok) throw new Error(`Failed to dismiss banner: ${response.status}`);
        const banner = document.getElementById('banner-live-notice')
        if (banner) banner.remove();
    } catch (error) {
        console.error('Failed to dismiss banner:', error);
    }
});

document.getElementById('get-started-button')?.addEventListener('click', async (e) => {
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
