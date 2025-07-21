<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'MudahJer') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <style>
            /* Copy all the styles from welcome.blade.php */
            :root[data-theme="emerald"] {
                --primary: #23577a;
                --secondary: #afd9e0;
                --accent: #63c196;
                --neutral: #fffcf3;
                --text-primary: #23577a;
                --text-secondary: rgba(35, 87, 122, 0.9);
                --bg-main: #fffcf3;
                --bg-secondary: rgba(35, 87, 122, 0.05);
                --bg-card: #ffffff;
                --border-color: #afd9e0;
                --footer-bg: #23577a;
                --footer-text: #fffcf3;
                --gradient-start: #fffcf3;
                --gradient-mid: rgba(175, 217, 224, 0.15);
                --gradient-end: rgba(99, 193, 150, 0.1);
                --btn-primary-bg: #23577a;
                --btn-primary-text: #ffffff;
                --btn-primary-hover: #1a4159;
            }

            :root[data-theme="dim"] {
                --primary: #afd9e0;
                --secondary: #23577a;
                --accent: #7ed4a6;
                --neutral: #1a1a1a;
                --text-primary: #e6e6e6;
                --text-secondary: rgba(230, 230, 230, 0.9);
                --bg-main: #121212;
                --bg-secondary: rgba(173, 217, 224, 0.05);
                --bg-card: #1e1e1e;
                --border-color: #2a2a2a;
                --footer-bg: #1a1a1a;
                --footer-text: #e6e6e6;
                --gradient-start: #121212;
                --gradient-mid: rgba(35, 87, 122, 0.15);
                --gradient-end: rgba(126, 212, 166, 0.1);
                --btn-primary-bg: #7ed4a6;
                --btn-primary-text: #1a1a1a;
                --btn-primary-hover: #63c196;
            }

            * {
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .gradient-text {
                background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                padding-bottom: 0.2rem;
            }

            .card {
                margin: 0 auto;
                padding: 2em;
                position: relative;
                border-radius: 10px;
                background: var(--bg-card);
                text-align: left;
                z-index: 1;
                width: 100%;
                max-width: 32rem;
                isolation: isolate;
            }

            .card::before {
                content: "";
                position: absolute;
                inset: -4px;
                background: conic-gradient(from var(--angle),
                    var(--primary),
                    var(--accent),
                    var(--secondary),
                    var(--primary));
                border-radius: 14px;
                z-index: -2;
                opacity: 1;
                animation: rotate 3s linear infinite;
                filter: drop-shadow(0 0 12px var(--accent));
            }

            .card::after {
                content: "";
                position: absolute;
                inset: -1px;
                background: var(--bg-card);
                border-radius: 11px;
                z-index: -1;
            }

            @keyframes rotate {
                from {
                    --angle: 0deg;
                }
                to {
                    --angle: 360deg;
                }
            }

            .auth-input {
                width: 100%;
                padding: 0.75rem;
                border-radius: 0.5rem;
                border: 1px solid var(--border-color);
                background: var(--bg-card);
                color: var(--text-primary);
                transition: border-color 0.2s ease;
            }

            .auth-input:focus {
                outline: none;
                border-color: var(--accent);
                box-shadow: 0 0 0 2px var(--accent-alpha);
            }

            .auth-button {
                background: var(--btn-primary-bg);
                color: var(--btn-primary-text);
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: background-color 0.2s ease;
            }

            .auth-button:hover {
                background: var(--btn-primary-hover);
            }

            .auth-link {
                color: var(--accent);
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .auth-link:hover {
                color: var(--primary);
            }

            /* Theme toggle button */
            .theme-toggle {
                background: none;
                border: none;
                padding: 0.5rem;
                cursor: pointer;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-primary);
                transition: background-color 0.3s ease;
            }

            .theme-toggle:hover {
                background-color: var(--bg-secondary);
            }

            .theme-toggle svg {
                width: 1.5rem;
                height: 1.5rem;
            }
        </style>
        <!-- Prevent theme flicker -->
        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        </script>
    </head>
    <body class="min-h-screen bg-[var(--bg-main)] text-[var(--text-primary)]">
        <x-navbar />

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 card">
                {{ $slot }}
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Theme toggle functionality
                const html = document.documentElement;
                const themeToggle = $('.theme-toggle');
                const sunIcon = $('.sun-icon');
                const moonIcon = $('.moon-icon');

                // Check for saved theme preference
                const savedTheme = localStorage.getItem('theme') || 'light';
                html.setAttribute('data-theme', savedTheme);
                updateThemeIcons(savedTheme);

                themeToggle.on('click', function() {
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    html.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcons(newTheme);
                });

                function updateThemeIcons(theme) {
                    if (theme === 'dark') {
                        sunIcon.addClass('hidden');
                        moonIcon.removeClass('hidden');
                    } else {
                        moonIcon.addClass('hidden');
                        sunIcon.removeClass('hidden');
                    }
                }
            });
        </script>
    </body>
</html>
