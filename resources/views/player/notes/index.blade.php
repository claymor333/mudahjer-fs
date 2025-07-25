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

        .card.hidden-card {
            display: none !important;
        }

        .quiz-card {
            min-height: 200px;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-base-content">
                    Available Signs
                </h2>
                <p class="mt-1 text-sm text-base-content/70">
                    Explore our collection of interactive sign language signs
                </p>
            </div>
            <div class="join join-vertical sm:join-horizontal gap-2 w-full sm:w-auto">
                <div class="join-item flex-1 sm:flex-none">
                    <select class="select select-bordered w-full" id="typeFilter">
                        <option value="all">All Content</option>
                        <option value="notes">Notes Only</option>
                        <option value="quiz">Quizzes Only</option>
                    </select>
                </div>
                <div class="join-item relative flex-1 sm:flex-none">
                    <input type="search" id="searchInput" 
                           class="input input-bordered w-full pr-20" 
                           placeholder="Search signs..." />
                    <div class="absolute top-0 right-0 join-item h-full flex items-center pr-2">
                        <kbd class="kbd kbd-sm">⌘</kbd>
                        <kbd class="kbd kbd-sm ml-1">K</kbd>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Beginner Quizzes Section -->

            @foreach($lessons as $lesson)
            <div class="mb-8 lesson-section" data-lesson-id="{{ $lesson->id }}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        {{-- <span class="badge badge-success badge-sm">Beginner</span> --}}
                        {{ $lesson->title }}
                    </h3>
                    <button class="btn btn-ghost btn-sm view-all-btn" data-lesson-id="{{ $lesson->id }}">
                        <span class="btn-text">View All</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 expand-icon" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 collapse-icon hidden" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>      
                    </button>
                </div>
                <div class="cards-container" data-lesson-id="{{ $lesson->id }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        <!-- Beginner Quiz Cards -->
                        @forelse($lesson->quizzes as $index => $quiz)
                        <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 w-full shadow-lg quiz-card {{ $index >= 5 ? 'hidden-card' : '' }}" data-lesson-id="{{ $lesson->id }}" data-card-index="{{ $index }}">
                            <div class="card-body">
                                <h2 class="card-title">{{ $quiz->title }}</h2>
                                <p class="mb-2">{{ $quiz->description }}</p>
                                <div class="card-actions justify-end">
                                    <a href="{{ route('player.notes.show', $quiz->id) }}" id="play-button-{!! $quiz->id !!}" class="btn btn-success btn-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" width="16" height="16"
                                            fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                            <path
                                                    d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="card card-border bg-base-200 dark:bg-base-300 w-full shadow-lg col-span-full">
                            <div class="card-body items-center text-center">
                                <h2 class="card-title">Coming Soon!</h2>
                                <p class="mb-2">Thanks for your patience!</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Include jQuery from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Store the original card states
            let originalCardStates = new Map();

            // Save original visibility states
            function saveOriginalStates() {
                $('.quiz-card').each(function() {
                    const cardId = $(this).attr('id') || $(this).data('lesson-id') + '-' + $(this).data('card-index');
                    originalCardStates.set(cardId, !$(this).hasClass('hidden-card'));
                });
            }

            // Initial save of states
            saveOriginalStates();

            // Type filter functionality
            $('#typeFilter').on('change', function() {
                const selectedType = $(this).val();
                const searchTerm = $('#searchInput').val().toLowerCase().trim();
                
                // Combine search and filter
                filterAndSearch(selectedType, searchTerm);
            });

            // Initialize view all button visibility
            $('.lesson-section').each(function() {
                const lessonId = $(this).data('lesson-id');
                const totalCards = $(this).find('.quiz-card').length;
                const viewAllBtn = $(this).find('.view-all-btn');
                
                // Hide view all button if there are 5 or fewer cards
                if (totalCards <= 5) {
                    viewAllBtn.hide();
                }
            });

            // View All / Collapse functionality
            $('.view-all-btn').on('click', function(e) {
                e.preventDefault();
                
                const lessonId = $(this).data('lesson-id');
                const lessonSection = $(`.lesson-section[data-lesson-id="${lessonId}"]`);
                const hiddenCards = lessonSection.find('.quiz-card.hidden-card');
                const btnText = $(this).find('.btn-text');
                const expandIcon = $(this).find('.expand-icon');
                const collapseIcon = $(this).find('.collapse-icon');
                
                if (hiddenCards.length > 0 && hiddenCards.first().is(':hidden')) {
                    // Expand: Show all cards
                    hiddenCards.removeClass('hidden-card').show();
                    btnText.text('Collapse');
                    expandIcon.addClass('hidden');
                    collapseIcon.removeClass('hidden');
                } else {
                    // Collapse: Hide cards beyond index 4
                    const cardsToHide = lessonSection.find('.quiz-card').slice(5);
                    cardsToHide.hide().addClass('hidden-card');
                    btnText.text('View All');
                    expandIcon.removeClass('hidden');
                    collapseIcon.addClass('hidden');
                }
            });

            // Add smooth scrolling to horizontal scroll containers (kept for compatibility)
            $('.scroll-container').each(function() {
                let isDown = false;
                let startX;
                let scrollLeft;
                const container = this;

                $(container).on('mousemove', function(e) {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });
            });

            // Focus search input with keyboard shortcut
            $(document).on('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    $('#searchInput').focus();
                }
            });

            // Search functionality with debounce
            let searchTimeout;
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                const selectedType = $('#typeFilter').val();
                
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterAndSearch(selectedType, searchTerm);
                }, 300);
            });

            function filterAndSearch(filterType, searchTerm) {
                let hasResults = false;
                const selectedType = filterType || $('#typeFilter').val();
                
                $('.lesson-section').each(function() {
                    let sectionHasResults = false;
                    const lessonSection = $(this);
                    
                    lessonSection.find('.quiz-card').each(function() {
                        const card = $(this);
                        const title = card.find('.card-title').text().toLowerCase();
                        const description = card.find('p').text().toLowerCase();
                        const type = card.data('type') || 'quiz';
                        
                        // Check if card matches both search term and filter
                        const matchesSearch = searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm);
                        const matchesFilter = selectedType === 'all' || selectedType === type;
                        const matches = matchesSearch && matchesFilter;
                        
                        if (matches || searchTerm === '') {
                            // Show matching cards and remove hidden-card class if searching
                            if (searchTerm !== '') {
                                card.removeClass('hidden-card').show();
                            } else {
                                // Reset to original state when search is cleared
                                const cardIndex = card.data('card-index');
                                if (cardIndex >= 5) {
                                    const viewAllBtn = lessonSection.find('.view-all-btn');
                                    const isExpanded = viewAllBtn.find('.btn-text').text() === 'Collapse';
                                    if (!isExpanded) {
                                        card.addClass('hidden-card').hide();
                                    } else {
                                        card.show();
                                    }
                                } else {
                                    card.show();
                                }
                            }
                            sectionHasResults = true;
                            hasResults = true;
                        } else {
                            card.hide();
                        }
                    });
                    
                    // Show/hide the entire section based on results
                    if (sectionHasResults) {
                        lessonSection.show();
                        // Hide view all button during search
                        if (searchTerm !== '') {
                            lessonSection.find('.view-all-btn').hide();
                        } else {
                            // Show view all button if there are more than 5 cards
                            const totalCards = lessonSection.find('.quiz-card').length;
                            if (totalCards > 5) {
                                lessonSection.find('.view-all-btn').show();
                            }
                        }
                    } else {
                        lessonSection.hide();
                    }
                });

                // Handle no results message
                $('#no-results-message').remove();
                
                if (!hasResults && searchTerm !== '') {
                    const noResultsHtml = `
                        <div id="no-results-message" class="text-center py-8">
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
                        </div>
                    `;
                    $('.max-w-7xl').append(noResultsHtml);
                }
            }
        });
    </script>
</x-app-layout>