/**
 * Dark Mode Toggle Handler
 */
(function() {
    'use strict';
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    const html = document.documentElement;
    
    // Apply theme on page load
    if (currentTheme === 'dark') {
        html.setAttribute('data-theme', 'dark');
    }
    
    // Create dark mode toggle button
    function initDarkModeToggle() {
        const navbar = document.querySelector('.navbar-nav.navbar-right');
        if (!navbar) return;
        
        const toggle = document.createElement('li');
        toggle.className = 'dropdown';
        toggle.innerHTML = `
            <a href="#" id="darkModeToggle" class="dropdown-toggle" title="Toggle Dark Mode">
                <i class="fa fa-${currentTheme === 'dark' ? 'sun' : 'moon'}-o"></i>
            </a>
        `;
        
        // Insert before user dropdown
        const userDropdown = navbar.querySelector('.dropdown:last-child');
        if (userDropdown) {
            navbar.insertBefore(toggle, userDropdown);
        } else {
            navbar.appendChild(toggle);
        }
        
        // Add click handler
        const toggleBtn = document.getElementById('darkModeToggle');
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
        });
    }
    
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Update icon
        const icon = document.querySelector('#darkModeToggle i');
        if (icon) {
            icon.className = `fa fa-${newTheme === 'dark' ? 'sun' : 'moon'}-o`;
        }
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: newTheme } }));
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkModeToggle);
    } else {
        initDarkModeToggle();
    }
})();

