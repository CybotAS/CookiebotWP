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

        if (data.status?.toLowerCase() === 'done') {
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

        if (response.status !== 200 && response.status !== 202 && response.status !== 404 || data.status === 'FAILED') {
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
        let userData = await fetch(`${API_BASE_URL}/accounts`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`,
            }
        }).then(r => r.json()).then(data => data[0]);

        if (!userData) throw new Error('No user data received');

        const storedUserData = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_get_user_data'),
            credentials: 'same-origin'
        }).then(r => r.json());

        if (storedUserData.data.subscriptions['active'].subscription_status.includes('trial') && !userData.subscriptions['active'].subscription_status.includes('trial')) {
            // window.trackAmplitudeEvent('Pricing Plan Chosen', {
            //     plan: userData.subscriptions['active'].price_plan,
            //     subscription_status: userData.subscriptions['active'].subscription_status
            // });
        }

        // Store user data
        userResponseData = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_post_user_data', {
                data: JSON.stringify(userData)
            }),
            credentials: 'same-origin'
        }).then(r => r.json());

        if (!userResponseData.success) {
            throw new Error('Failed to store user data');
        }

        return userResponseData;
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
        if (response.status === 401 && cookiebot_account.has_user_data) {
            await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_delete_auth_token'),
                credentials: 'same-origin'
            });
            if (!window.prevent_default && canReload()) {
                window.location.reload();
            }
        }
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

function canReload() {    
    const itemStr = localStorage.getItem('dashboard_reload');
    const now = new Date();
    const numTimes = 3;
    const ttl = 30000;
    let count = 0;

    if (itemStr) {
        const item = JSON.parse(itemStr);
    
        if (now.getTime() < item.exp) {
            if (item.count > numTimes) {
                return false;    
            }
            count = item.count + 1;
        }
    }
    
    const item = {
        count: count,
        exp: now.getTime() + ttl,
    }
    localStorage.setItem('dashboard_reload', JSON.stringify(item));
    return true;
}

document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ucApiCode = urlParams.get('uc_api_code');
    const companyId = urlParams.get('company_id');

    // if (!cookiebot_account.has_cbid) {
    //     window.trackAmplitudeEvent('Get Started Page Visited');
    // }
    // For existing users, no account registration workflow is needed 
    if (cookiebot_account.has_cbid && !cookiebot_account.has_user_data && !cookiebot_account.was_onboarded) {
        return;
    }

    if (cookiebot_account.cbid !== cookiebot_account.scan_id) {
        await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_update_scan_id', {
                scan_id: cookiebot_account.cbid
            }),
            credentials: 'same-origin'
        });
        if (canReload()) {
            window.location.reload();
        }
    }

    // For previously onboarded users that disconnected the account, no new account registration workflow is needed 
    // if (!cookiebot_account.has_cbid && cookiebot_account.was_onboarded) {
    //     return;
    // }

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
                    if (canReload()) {
                        const newUrl = new URL(window.location.href);
                        newUrl.searchParams.delete('uc_api_code');
                        newUrl.searchParams.delete('is_new_user');
                        window.location.href = newUrl;    
                    }
                    return;
                } catch (error) {
                    console.error('Failed to process authentication:', error);
                    return;
                }
            }


            if (isNewUser === 'false' && !cookiebot_account.has_user_data) {
                await fetch(cookiebot_account.ajax_url, {
                    method: 'POST',
                    body: createFormData('cookiebot_process_auth_code', { code: ucApiCode }),
                    credentials: 'same-origin'
                });

                if (canReload()) {
                    const settingsUrl = window.location.protocol + '//' + window.location.hostname + '/wp-admin/admin.php?page=cookiebot_settings';
                    window.location.href = settingsUrl;
                    return;
                }
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

        if (!cbid) {
            try {
                if (!companyId) {
                    // Without company ID we cannot create the other config params, so stop the workflow right here.
                    return;
                }

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
                        configuration_name: "",
                        data_controller: "",
                        legal_framework_template: "gdpr",
                        domains: [formattedDomain],
                        company_id: companyId
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
            if (cbid) {
                await fetchConfigurationDetails(cbid);
                await checkUserData();
            }
        }

        // Check and fetch user data if needed
        let userResponseData = await fetch(cookiebot_account.ajax_url, {
            method: 'POST',
            body: createFormData('cookiebot_get_user_data'),
            credentials: 'same-origin'
        }).then(r => r.json());

        try {
            let userData = await fetch(`${API_BASE_URL}/accounts`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                }
            }).then(r => r.json()).then(data => data[0]);

            // Track account creation in Amplitude
            // window.trackAmplitudeEvent('Account Created');

            // Store user data
            userResponseData = await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_post_user_data', {
                    data: JSON.stringify(userData)
                }),
                credentials: 'same-origin'
            }).then(r => r.json());

            // Store onboarding status separately
            await fetch(cookiebot_account.ajax_url, {
                method: 'POST',
                body: createFormData('cookiebot_store_onboarding_status', {
                    onboarded: true
                }),
                credentials: 'same-origin'
            });

        } catch (error) {
            console.error('Failed to fetch user data:', error);
            return;
        }

        if (userResponseData.data && canReload()) {
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
        const banner = document.getElementById('banner-live-notice');
        if (banner) banner.remove();
    } catch (error) {
        console.error('Failed to dismiss banner:', error);
    }
});

document.getElementById('get-started-button')?.addEventListener('click', async (e) => {
    // window.trackAmplitudeEvent('Sign Up Flow Started');
    e.preventDefault();
    try {
        const callbackUrl = window.location.href;
        window.location.href = `${API_BASE_URL}/auth/auth0/authorize?origin=wordpress_plugin&callback_domain=${encodeURIComponent(callbackUrl)}`;
    } catch (error) {
        console.error('Failed to start authentication process:', error);
    }
});