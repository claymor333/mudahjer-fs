<x-app-layout>
    <style>
        .streak-flame {
            animation: flicker 2s infinite alternate;
        }
        
        @keyframes flicker {
            0%, 100% { transform: scale(1) rotate(-1deg); }
            50% { transform: scale(1.05) rotate(1deg); }
        }
        
        .card {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(to bottom right, 
                rgba(255, 255, 255, 0.2), 
                rgba(255, 255, 255, 0.05)
            );
        }
        
        .card-hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stats {
            background: linear-gradient(145deg, 
                rgba(255, 255, 255, 0.1), 
                rgba(255, 255, 255, 0.05)
            );
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Dashboard') }}
            </h2>
            
            <!-- Streak Counter -->
            <div class="flex items-center gap-2 bg-gradient-to-r from-orange-400 to-red-500 text-white px-4 py-2 rounded-full shadow-lg">
                <div class="streak-flame text-2xl">🔥</div>
                <div class="text-center">
                    <div class="text-lg font-bold">15</div>
                    <div class="text-xs opacity-90">day streak</div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 bg-gradient-to-br from-base-200 via-base-100 to-base-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Additional Stats Section -->
            <div class="mb-8">
                <div class="stats stats-vertical lg:stats-horizontal shadow-lg w-full backdrop-blur-lg">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <div class="avatar online">
                                <div class="w-16 rounded-full">
                                    <div class="bg-secondary text-secondary-content rounded-full w-16 h-16 flex items-center justify-center">
                                        <span class="text-2xl">🎯</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-title">Total Quizzes</div>
                        <div class="stat-value text-secondary">127</div>
                        <div class="stat-desc">Completed this month</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <div class="avatar">
                                <div class="w-16 rounded-full">
                                    <div class="bg-accent text-accent-content rounded-full w-16 h-16 flex items-center justify-center">
                                        <span class="text-2xl">📈</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-title">Accuracy Rate</div>
                        <div class="stat-value text-accent">89%</div>
                        <div class="stat-desc">↗️ 12% improvement</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <div class="avatar">
                                <div class="w-16 rounded-full">
                                    <div class="bg-info text-info-content rounded-full w-16 h-16 flex items-center justify-center">
                                        <span class="text-2xl">⭐</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-title">Level</div>
                        <div class="stat-value text-info">Intermediate</div>
                        <div class="stat-desc">Level 7 of 10</div>
                    </div>
                </div>
            </div>

            @php
                $hasAdmin = auth()->user()->hasRole('admin');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-{{ $hasAdmin ? '3' : '2' }} gap-8">

                <!-- Admin Card -->
                @hasrole('admin')
                <div class="card shadow-xl card-hover backdrop-blur-lg">
                    <div class="card-body items-center text-center">
                        <div class="avatar placeholder mb-4">
                            <div class="bg-primary text-primary-content rounded-full w-16">
                                <span class="text-2xl">⚙️</span>
                            </div>
                        </div>
                        <h3 class="card-title text-base-content">Admin Controls</h3>
                        <p class="text-base-content/70 mb-4">Manage content and settings</p>
                        <div class="card-actions">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-circle btn-lg hover:rotate-90 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endhasrole

                <!-- Quiz/Learn Card -->
                <div class="card shadow-xl card-hover backdrop-blur-lg">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="avatar placeholder mr-4">
                                <div class="bg-accent text-accent-content rounded-full w-12">
                                    <span class="text-xl">🧠</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="card-title text-base-content">Quiz & Learn</h3>
                                <div class="badge badge-secondary badge-sm">Interactive</div>
                            </div>
                        </div>
                        <p class="text-base-content/70 mb-4">
                            Test your sign language knowledge with interactive quizzes and learn new signs through engaging lessons.
                        </p>
                        <div class="card-actions justify-end">
                            <button class="btn btn-accent btn-sm">Explore Quizzes</button>
                        </div>
                    </div>
                </div>

                <!-- Dictionary Card -->
                <div class="card shadow-xl card-hover backdrop-blur-lg">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="avatar placeholder mr-4">
                                <div class="bg-info text-info-content rounded-full w-12">
                                    <span class="text-xl">📚</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="card-title text-base-content">Dictionary</h3>
                                <div class="badge badge-info badge-sm">Reference</div>
                            </div>
                        </div>
                        <p class="text-base-content/70 mb-4">
                            Comprehensive sign language dictionary with video demonstrations and detailed descriptions.
                        </p>
                        <div class="mb-4">
                            <div class="stats stats-horizontal shadow">
                                <div class="stat p-2">
                                    <div class="stat-title text-xs">Total Signs</div>
                                    <div class="stat-value text-sm">2,847</div>
                                </div>
                                <div class="stat p-2">
                                    <div class="stat-title text-xs">Categories</div>
                                    <div class="stat-value text-sm">24</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-actions justify-end">
                            <button class="btn btn-info btn-sm">Browse Dictionary</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="mt-8">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <span class="text-2xl mr-2">📊</span>
                            Weekly Progress
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Quiz Completion</span>
                                    <span>75%</span>
                                </div>
                                <progress class="progress progress-primary" value="75" max="100"></progress>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Dictionary Study</span>
                                    <span>60%</span>
                                </div>
                                <progress class="progress progress-secondary" value="60" max="100"></progress>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Practice Sessions</span>
                                    <span>85%</span>
                                </div>
                                <progress class="progress progress-accent" value="85" max="100"></progress>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('button')) {
                        console.log('Card clicked:', this);
                    }
                });
            });
        });
    </script>
</x-app-layout>