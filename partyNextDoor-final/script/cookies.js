document.addEventListener("DOMContentLoaded", () => {
    // Check if the user has already accepted or declined cookies
    const cookieConsent = getCookie('cookieConsent');
    console.log('cookieConsent:', cookieConsent); 
    if (!cookieConsent) {
        document.getElementById('cookie-popup').style.display = 'block';
    }

    // Function to set a cookie
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
        console.log(`Cookie set: ${name}=${value}`);
    }

    // Function to get a cookie
    function getCookie(name) {
        const cookies = document.cookie.split('; ');
        console.log('All cookies:', cookies);
        for (let cookie of cookies) {
            const [key, value] = cookie.split('=');
            if (key === name) {
                console.log(`Cookie found: ${key}=${value}`); 
                return value;
            }
        }
        return null;
    }

    // Handle cookie consent buttons
    document.getElementById('accept-cookies').addEventListener('click', () => {
        setCookie('cookieConsent', 'accepted', 30); 
        document.getElementById('cookie-popup').style.display = 'none';
    });

    document.getElementById('decline-cookies').addEventListener('click', () => {
        setCookie('cookieConsent', 'declined', 30); 
        document.getElementById('cookie-popup').style.display = 'none';
    });
});
