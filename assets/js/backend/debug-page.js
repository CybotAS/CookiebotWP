function copyDebugInfo() {
    const t = document.getElementById( 'cookiebot-debug-info' )
    t.select()
    t.setSelectionRange( 0, 99999 )
    document.execCommand( 'copy' )
}

async function clearConfigData() {
    try {
        let formData = new FormData();
        formData.append('action', 'cookiebot_clear_config_data');

        const response = await fetch('admin-ajax.php', {
            method: 'POST',
            body: formData
        });
        window.location.href = 'admin.php?page=cookiebot';

    } catch (error) {
        console.error(`Failed to clear config data:`, error);
    }
}

async function clearConfigDataKeepCbid() {
    try {
        let formData = new FormData();
        formData.append('action', 'cookiebot_clear_config_data_keep_cbid');

        const response = await fetch('admin-ajax.php', {
            method: 'POST',
            body: formData
        });
        window.location.href = 'admin.php?page=cookiebot';

    } catch (error) {
        console.error(`Failed to clear config data:`, error);
    }
}