<x-app-layout>
    <style>
        .streak-flame {
            animation: flicker 2s infinite alternate;
        }
        
        @keyframes flicker {
            0%, 100% { transform: scale(1) rotate(-1deg); }
            50% { transform: scale(1.05) rotate(1deg); }
        }
/*         
        .card {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(to bottom right, 
                rgba(255, 255, 255, 0.2), 
                rgba(255, 255, 255, 0.05)
            );
        } */
        
        .card-hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* .stats {
            background: linear-gradient(145deg, 
                rgba(255, 255, 255, 0.1), 
                rgba(255, 255, 255, 0.05)
            );
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        } */
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
                    <div class="text-lg font-bold">{{ $streakDays }}</div>
                    <div class="text-xs opacity-90">day streak</div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-base-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Additional Stats Section -->
            <div class="mb-8">
                <div class="stats stats-vertical lg:stats-horizontal border border-base-300 dark:bg-base-200 shadow-lg w-full backdrop-blur-lg mb-8">
                    <div class="stat">
                        <div class="stat-title">Total Quizzes</div>
                        <div class="stat-value text-secondary">{{ $totalQuizzes }}</div>
                        <div class="stat-desc">Completed this month</div>
                    </div>

                    <div class="stat">
                        <div class="stat-title">Accuracy Rate</div>
                        <div class="stat-value text-accent">{{ $accuracy }}%</div>
                        <div class="stat-desc">Across all completed quizzes</div>
                    </div>

                    <div class="stat">
                        <div class="stat-title">Level</div>
                        <div class="stat-value text-info">Level {{ $level }}</div>
                        <div class="stat-desc">
                            {{ $inProgressCount }} lessons at 100%
                            @if ($canAdvance)
                                • <span class="text-success">Ready for next level</span>
                            @else
                                • <span class="text-warning">Complete at least one</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @hasrole('admin')
            <div class="grid grid-cols-3 gap-6">
    <!-- Admin Card -->
    <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 shadow-lg">
        <div class="card-body items-center text-center">
            <div class="avatar placeholder mb-4">
                <div class="bg-primary text-primary-content rounded-full w-12 p-2">
                    <!-- Settings Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-8 h-8" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                    </svg>
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

    @elsehasrole('user')
    <div class="grid grid-cols-2 gap-6">
    @endhasrole

    <!-- Quiz/Learn Card -->
    <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 shadow-lg">
        <div class="card-body">
            <div class="flex items-center mb-4">
                <div class="avatar placeholder mr-4">
                    <div class="bg-accent text-accent-content rounded-full w-12 p-2">
                        <!-- Brain Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-8 h-8" viewBox="0 0 16 16">
                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                        </svg>
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
                <a href="{{ route('player.quizzes.index') }}" class="btn btn-accent btn-sm">
                    Explore Quizzes
                </a>
            </div>
        </div>
    </div>

    <!-- Dictionary Card -->
    <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 shadow-lg">
        <div class="card-body">
            <div class="flex items-center mb-4">
                <div class="avatar placeholder mr-4">
                    <div class="bg-info text-info-content rounded-full w-12 p-2">
                        <!-- Book Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-8 h-8" viewBox="0 0 16 16">
                            <path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5z"/>
                        </svg>
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
                        <div class="stat-value text-sm">{{ $signs }}</div>
                    </div>
                    <div class="stat p-2">
                        <div class="stat-title text-xs">Categories</div>
                        <div class="stat-value text-sm">{{ $categories }}</div>
                    </div>
                </div>
            </div>
            <div class="card-actions justify-end">
                <a href="{{ route('player.notes.index') }}" class="btn btn-info btn-sm">Browse Dictionary</a>
            </div>
        </div>
    </div>
</div>


            <!-- Progress Section -->
            {{-- <div class="mt-8">
                <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 shadow-lg">
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
            </div> --}}
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