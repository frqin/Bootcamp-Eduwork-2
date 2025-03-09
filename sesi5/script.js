// Handle login form submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const logoutBtn = document.getElementById('logoutBtn');

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get user input
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');

            // Simulated authentication (can be replaced with backend authentication)
            if (username === 'danifirqin' && password === '210804') {
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('username', username);

                // Redirect to home page
                window.location.href = 'sesi5.html';
            } else {
                errorMessage.textContent = 'Username atau password salah!';
            }
        });
    }

    // Check login status on home page
    if (window.location.pathname.includes('home.html')) {
        const isLoggedIn = localStorage.getItem('isLoggedIn');

        if (!isLoggedIn) {
            // Redirect to login page if not logged in
            window.location.href = 'login.html';
        }
    }

    // Handle logout
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('username');

            // Redirect to login page
            window.location.href = 'login.html';
        });
    }
});
