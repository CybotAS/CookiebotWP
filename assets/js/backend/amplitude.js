// Load Amplitude SDK
const script = document.createElement('script');
script.src = 'https://cdn.eu.amplitude.com/script/3573fa11b8c5b4bcf577ec4c8e9d5cb6.js';
script.async = true;
script.onload = function () {
    window.amplitude.init('3573fa11b8c5b4bcf577ec4c8e9d5cb6', {
        serverZone: 'EU',
        fetchRemoteConfig: true,
        defaultTracking: false
    });
};
document.head.appendChild(script);

// Global tracking function
window.trackAmplitudeEvent = function (eventName, additionalProperties = {}) {
    try {
        if (window.amplitude?.track) {
            const properties = {
                source: 'wordpress_plugin',
                timestamp: new Date().toISOString(),
                ...additionalProperties
            };

            window.amplitude.track(eventName, properties);
        } else {
        }
    } catch (error) {
    }
}
