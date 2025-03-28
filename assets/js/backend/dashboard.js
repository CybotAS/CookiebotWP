jQuery(document).ready(function($) {
    // Handle toggle functionality
    function handleToggle(toggleId, action, value) {
        const toggle = document.getElementById(toggleId);
        if (!toggle) {
            console.error(`Toggle element not found: ${toggleId}`);
            return;
        }

        toggle.addEventListener('click', async function(event) {
            const isEnabled = this.checked;
            const badgeId = toggleId === 'cookiebot-banner-enabled' ? 'cookiebot-banner-badge' : `${toggleId}-badge`;
            const badge = document.getElementById(badgeId);
            if (!badge) {
                console.error(`Badge element not found: ${badgeId}`);
            }
            
            console.log(`Toggle ${toggleId} clicked. New state:`, isEnabled);
            
            try {
                const response = await $.ajax({
                    url: cookiebot_account.ajax_url,
                    method: 'POST',
                    data: {
                        action: action,
                        nonce: cookiebot_account.nonce,
                        value: isEnabled ? value : '0'
                    }
                });

                console.log(`Toggle ${toggleId} response:`, response);

                if (!response.success) {
                    throw new Error(response.data || `Failed to update ${toggleId} status`);
                }

                // Update badge status
                if (badge) {
                    badge.className = `label-wrapper status-badge ${isEnabled ? 'active' : 'inactive'}`;
                    badge.querySelector('.label-2').textContent = isEnabled ? 'Active' : 'Inactive';
                    console.log(`Updated badge for ${toggleId}. New state:`, isEnabled ? 'active' : 'inactive');
                }

                if (cookiebot_account.debug) {
                    console.log(`${toggleId} toggle response:`, response);
                }
            } catch (error) {
                console.error(`Failed to toggle ${toggleId}:`, error);
                this.checked = !isEnabled;
                // Revert badge status on error
                if (badge) {
                    badge.className = `label-wrapper status-badge ${!isEnabled ? 'active' : 'inactive'}`;
                    badge.querySelector('.label-2').textContent = !isEnabled ? 'Active' : 'Inactive';
                    console.log(`Reverted badge for ${toggleId}. State:`, !isEnabled ? 'active' : 'inactive');
                }
            }
        });
    }

    handleToggle('cookiebot-banner-enabled', 'cookiebot_set_banner_enabled', '1');
    handleToggle('cookiebot-gcm', 'cookiebot_set_gcm_enabled', '1');

    // Handle expand/collapse
    const expandToggle = document.querySelector('.expand-toggle');
    if (expandToggle) {
        expandToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            const targetId = this.getAttribute('aria-controls');
            const targetSection = document.getElementById(targetId);
            const arrowIcon = this.querySelector('.arrow-icon');
            
            this.setAttribute('aria-expanded', !expanded);
            targetSection.style.display = expanded ? 'none' : 'block';
            arrowIcon.style.transform = expanded ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    }
}); 