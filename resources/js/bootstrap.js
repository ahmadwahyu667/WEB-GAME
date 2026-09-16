// resources/js/bootstrap.js
// Default headers for standard fetch and ajax requests
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    window.csrfToken = token;
}
