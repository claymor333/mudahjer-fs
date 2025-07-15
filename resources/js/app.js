import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Theme switcher functionality
$(document).ready(function() {
    // Set initial theme based on localStorage or system preference
    const theme = localStorage.getItem('theme') || 
                 (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    setTheme(theme);

    // Listen for changes on the theme controller checkbox
    $('.theme-controller').change(function() {
        const newTheme = $(this).prop('checked') ? 'dark' : 'light';
        setTheme(newTheme);
    });

    // Add mobile theme toggle support
    $('#theme-toggle-mobile').click(function() {
        const currentTheme = $('body').attr('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    });

    // Theme switching function
    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Update checkbox state
        $('.theme-controller').prop('checked', theme === 'dark');
    }
});
