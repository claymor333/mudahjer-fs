<x-app-layout>
    <style>
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

        /* Theme transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Base text styles */
        body {
            line-height: 1.6;
        }

        p {
            line-height: 1.8;
            margin-bottom: 0.5rem;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        h1 {
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero h1 {
            line-height: 1.2;
            padding-bottom: 0.1em;
        }

        .card-title {
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .stat-title,
        .stat-value {
            line-height: 1.4;
            padding: 0.25rem 0;
        }

        .menu li > * {
            line-height: 1.4 !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .brand-gradient {
            background: linear-gradient(135deg, var(--primary), var(--accent));
        }

        .glow-effect {
            position: relative;
        }
        .glow-effect::before {
            content: "";
            position: absolute;
            inset: -1px;
            background: linear-gradient(45deg, var(--secondary), var(--accent));
            border-radius: inherit;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .glow-effect:hover::before {
            opacity: 1;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding-bottom: 0.2rem; /* Add padding to prevent cutoff */
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        .bg-brand-primary {
            background-color: var(--primary);
        }
        .bg-brand-secondary {
            background-color: var(--secondary);
        }
        .bg-brand-accent {
            background-color: var(--accent);
        }
        .bg-brand-neutral {
            background-color: var(--neutral);
        }

        .text-brand-primary {
            color: var(--primary);
        }
        .text-brand-secondary {
            color: var(--secondary);
        }
        .text-brand-accent {
            color: var(--accent);
        }
        .text-brand-neutral {
            color: var(--neutral);
        }

        /* Card body padding adjustments */
        .card-body {
            padding: 1.5rem;
            line-height: 1.6;
        }

        @property --angle {
            syntax: "<angle>";
            initial-value: 0deg;
            inherits: false;
        }

        .card {
            margin: 0 auto;
            padding: 2em;
            position: relative;
            border-radius: 10px;
            background: var(--bg-card);
            text-align: left;
            transition: transform 0.2s ease;
            z-index: 1;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            isolation: isolate;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: -4px;
            background: conic-gradient(
                from var(--angle),
                var(--primary),
                var(--accent),
                var(--secondary),
                var(--primary)
            );
            border-radius: 14px;
            z-index: -2;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .card::after {
            content: "";
            position: absolute;
            inset: -1px;
            background: var(--bg-card);
            border-radius: 11px;
            z-index: -1;
        }

        .card > * {
            position: relative;
            z-index: 1;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card:hover::before {
            opacity: 1;
            animation: rotate 3s linear infinite;
            filter: drop-shadow(0 0 12px var(--accent));
        }

        @keyframes rotate {
            from {
                --angle: 0deg;
            }
            to {
                --angle: 360deg;
            }
        }

        /* Stats adjustments */
        .stats {
            padding: 1rem;
        }

        .stat {
            padding: 1rem;
        }

        /* Button text adjustments */
        .btn {
            line-height: 1.4;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
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

        @keyframes blob {
            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }
            25% {
                transform: translate(20px, -20px) scale(1.05);
            }
            50% {
                transform: translate(0, 20px) scale(0.95);
            }
            75% {
                transform: translate(-20px, -20px) scale(1.05);
            }
        }

        .animate-blob {
            animation: blob 15s infinite ease-in-out;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .card-body {
            width: 100%;
            text-align: left;
        }

        .card-title {
            text-align: left;
            width: 100%;
        }

        .card figure {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>

    <!-- Hero Section -->
    <div class="hero min-h-screen bg-[var(--bg-main)] relative overflow-hidden">
        <!-- Gradient Background -->
        <div class="absolute inset-0" style="
            background: radial-gradient(circle at top left, var(--gradient-mid) 0%, transparent 40%),
                       radial-gradient(circle at top right, var(--gradient-end) 0%, transparent 40%),
                       linear-gradient(to bottom, var(--gradient-start), var(--bg-main));">
        </div>
        <!-- Animated Blobs -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-[15%] left-[10%] w-[40rem] h-[40rem] bg-[var(--secondary)] rounded-full filter blur-[128px] animate-blob"></div>
            <div class="absolute bottom-[15%] right-[10%] w-[40rem] h-[40rem] bg-[var(--accent)] rounded-full filter blur-[128px] animate-blob animation-delay-2000"></div>
        </div>
        <div class="hero-content flex-col lg:flex-row-reverse gap-12 relative z-10 py-20">
            <div class="hero-image-container">
                <div class="relative">
                    <img src="{{ asset('images/hero-laptop.png') }}" class="max-w-2xl w-full rounded-lg" alt="MudahJer on Laptop" />
                    <img src="{{ asset('images/hero-phone.png') }}" class="absolute -bottom-12 -right-12 w-56 rounded-lg" alt="MudahJer on Phone" />
                </div>
            </div>
            <div class="max-w-2xl text-center lg:text-left">
                <h1 class="text-5xl md:text-6xl font-bold mb-8 gradient-text">Learn Sign Language the Interactive Way</h1>
                <p class="py-6 text-lg opacity-80">Master sign language through interactive flashcards and real-time practice. Join our community and break down communication barriers today.</p>
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                    <button class="btn bg-[var(--btn-primary-bg)] text-[var(--btn-primary-text)] hover:bg-[var(--btn-primary-hover)] border-none btn-lg gradient-outline">Start Learning Now</button>
                    <button class="btn btn-ghost btn-lg hover:bg-[var(--bg-secondary)]">View Dictionary</button>
                </div>
                <div class="mt-8 stats shadow-lg bg-[var(--bg-card)] border border-[var(--border-color)]">
                    <div class="stat">
                        <div class="stat-title text-[var(--text-primary)]">Active Learners</div>
                        <div class="stat-value text-[var(--accent)]">5K+</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title text-[var(--text-primary)]">Signs to Learn</div>
                        <div class="stat-value text-[var(--accent)]">1000+</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title text-[var(--text-primary)]">Daily Practice</div>
                        <div class="stat-value text-[var(--accent)]">15K+</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 relative bg-[var(--bg-secondary)]">
        <div class="container mx-auto px-4 relative z-10">
            <h2 class="text-4xl font-bold text-center mb-12 gradient-text">How MudahJer Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card bg-[var(--bg-card)] shadow-xl card-hover">
                    <figure class="px-6 pt-6">
                        <img src="{{ asset('images/hero-quiz.webp') }}" alt="Interactive Learning" class="rounded-xl hand-sign" />
                    </figure>
                    <div class="card-body">
                        <div class="text-4xl mb-4">👋</div>
                        <h2 class="card-title text-[var(--text-primary)]">Learn</h2>
                        <p class="text-[var(--text-secondary)]">Start with basic signs through our interactive flashcard system.</p>
                    </div>
                </div>
                <div class="card bg-[var(--bg-card)] shadow-xl card-hover">
                    <figure class="px-6 pt-6">
                        <img src="{{ asset('images/hero-quiz.webp') }}" alt="Practice" class="rounded-xl hand-sign" />
                    </figure>
                    <div class="card-body">
                        <div class="text-4xl mb-4">✍️</div>
                        <h2 class="card-title text-[var(--text-primary)]">Practice</h2>
                        <p class="text-[var(--text-secondary)]">Test your knowledge with interactive quizzes and real-time feedback.</p>
                    </div>
                </div>
                <div class="card bg-[var(--bg-card)] shadow-xl card-hover">
                    <figure class="px-6 pt-6">
                        <img src="{{ asset('images/hero-quiz.webp') }}" alt="Master" class="rounded-xl hand-sign" />
                    </figure>
                    <div class="card-body">
                        <div class="text-4xl mb-4">🌟</div>
                        <h2 class="card-title text-[var(--text-primary)]">Master</h2>
                        <p class="text-[var(--text-secondary)]">Track your progress and master sign language at your own pace.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="py-20 relative overflow-hidden bg-[var(--bg-main)]">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 gradient-text">Popular Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="card bg-[var(--bg-card)] shadow-lg border border-[var(--border-color)] card-hover">
                    <div class="card-body flex flex-col items-center justify-center text-center w-full">
                        <div class="text-4xl mb-2">🔤</div>
                        <h3 class="card-title justify-center">Alphabet</h3>
                        <p class="text-center">26 Signs</p>
                    </div>
                </div>
                <div class="card bg-[var(--bg-card)] shadow-lg border border-[var(--border-color)] card-hover">
                    <div class="card-body flex flex-col items-center justify-center text-center w-full">
                        <div class="text-4xl mb-2">👋</div>
                        <h3 class="card-title justify-center">Greetings</h3>
                        <p class="text-center">15 Signs</p>
                    </div>
                </div>
                <div class="card bg-[var(--bg-card)] shadow-lg border border-[var(--border-color)] card-hover">
                    <div class="card-body flex flex-col items-center justify-center text-center w-full">
                        <div class="text-4xl mb-2">🏠</div>
                        <h3 class="card-title justify-center">Common Words</h3>
                        <p class="text-center">100+ Signs</p>
                    </div>
                </div>
                <div class="card bg-[var(--bg-card)] shadow-lg border border-[var(--border-color)] card-hover">
                    <div class="card-body flex flex-col items-center justify-center text-center w-full">
                        <div class="text-4xl mb-2">💬</div>
                        <h3 class="card-title justify-center">Phrases</h3>
                        <p class="text-center">50+ Signs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer footer-center p-10 bg-[var(--footer-bg)] text-[var(--footer-text)]">
        <nav class="grid grid-flow-col gap-4">
            <a class="link link-hover hover:text-[#63c196] transition-colors">About us</a>
            <a class="link link-hover hover:text-[#63c196] transition-colors">Contact</a>
            <a class="link link-hover hover:text-[#63c196] transition-colors">Privacy Policy</a>
            <a class="link link-hover hover:text-[#63c196] transition-colors">Terms of Use</a>
        </nav> 
        <nav>
            <div class="grid grid-flow-col gap-4">
                <a class="btn btn-ghost btn-circle hover:bg-[#afd9e0]/20"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg></a>
                <a class="btn btn-ghost btn-circle hover:bg-[#afd9e0]/20"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></a>
                <a class="btn btn-ghost btn-circle hover:bg-[#afd9e0]/20"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
            </div>
        </nav> 
        <aside>
            <p>Copyright © {{ date('Y') }} - All rights reserved by <span class="text-[#63c196] font-bold">MudahJer</span></p>
        </aside>
    </footer>
</x-app-layout>