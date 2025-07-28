<x-app-layout>
    <style>
        .scroll-container {
            -ms-overflow-style: none;
            /* Hide scrollbar IE and Edge */
            scrollbar-width: none;
            /* Hide scrollbar Firefox */
        }

        .scroll-container::-webkit-scrollbar {
            display: none;
            /* Hide scrollbar Chrome, Safari, Opera */
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-base-content">
                    Available Quizzes
                </h2>
                <p class="mt-1 text-sm text-base-content/70">
                    Explore our collection of interactive sign language quizzes
                </p>
            </div>
            <label class="input">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
                <input type="search" id="searchInput" class="grow" placeholder="Search signs..." />
                <kbd class="kbd kbd-sm">CTRL</kbd>
                <kbd class="kbd kbd-sm">K</kbd>
            </label>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Beginner Quizzes Section -->

            @foreach($lessons as $lesson)
                                            @if(auth()->user()->player->level < $lesson->required_level)
                                                    <div class="badge badge-error badge-sm mb-2">Locked</div>
                                            @endif

                                            @forelse($quiz->lessonplayerquiz as $submission)
                                                @if($submission->is_completed)
                                                    <div class="badge badge-primary badge-sm mb-2">Complete</div>
                                                @else
                                                    <div class="badge badge-warning badge-sm mb-2">Incomplete</div>
                                                @endif
                                            @empty
                                            @endforelse
                                            <div class="card-actions justify-end">
                                                <a href="{{ route('player.quizzes.play', $quiz->id) }}" class="btn btn-success btn-circle {{ auth()->user()->player->level < $lesson->required_level ? 'btn-disabled' : '' }}" title="Start Quiz">
                                                    @forelse($quiz->lessonplayerquiz as $submission)
                                                        @if($submission->is_completed)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/>
                                                                <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"/>
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393"/>
                                                            </svg>
                                                        @endif
                                                    @empty
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393"/>
                                                        </svg>
                                                    @endforelse
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to horizontal scroll containers
            const scrollContainers = document.querySelectorAll('.scroll-container');
            scrollContainers.forEach(container => {
                let isDown = false;
                let startX;
                let scrollLeft;

                container.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });
            });

        // Search functionality
            const searchInput = document.getElementById('searchInput');
            const quizCards = document.querySelectorAll('.card');
            let searchTimeout;

            // Focus search input with keyboard shortcut
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            // Search implementation
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(() => {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    let hasResults = false;

                    // Get all lesson sections
                    const lessonSections = document.querySelectorAll('.mb-8');
                    
                    lessonSections.forEach(section => {
                        let sectionHasResults = false;
                        const cards = section.querySelectorAll('.card');
                        
                        cards.forEach(card => {
                            const title = card.querySelector('.card-title').textContent.toLowerCase();
                            const description = card.querySelector('p').textContent.toLowerCase();
                            const matches = title.includes(searchTerm) || description.includes(searchTerm);
                            
                            if (matches) {
                                card.style.display = '';
                                sectionHasResults = true;
                                hasResults = true;
                            } else {
                                card.style.display = 'none';
                            }
                        });
                        
                        // Show/hide the entire section based on results
                        section.style.display = sectionHasResults ? '' : 'none';
                    });

                    // Show no results message if needed
                    const existingNoResults = document.getElementById('no-results-message');
                    if (!hasResults && !existingNoResults && searchTerm !== '') {
                        const noResults = document.createElement('div');
                        noResults.id = 'no-results-message';
                        noResults.className = 'text-center py-8';
                        noResults.innerHTML = `
                            <div class="flex flex-col items-center gap-4">
                                <div class="text-base-content/70">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold">No signs found</h3>
                                <p class="text-base-content/70">Try adjusting your search terms</p>
                            </div>
                        `;
                        document.querySelector('.max-w-7xl').appendChild(noResults);
                    } else if (hasResults && existingNoResults) {
                        existingNoResults.remove();
                    }
                }, 300); // Debounce delay
            });
        });
    </script>
</x-app-layout>