<x-app-layout>
    <style>
        .scroll-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scroll-container::-webkit-scrollbar {
            display: none;
        }

        .card.hidden-card {
            display: none !important;
        }

        .quiz-card, .note-card {
            min-height: 200px;
        }

        .note-card figure {
            height: 200px;
            overflow: hidden;
        }

        .note-card figure img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <!-- Main Header -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div>
                    <h2 class="font-semibold text-xl text-base-content">
                        Available Signs
                    </h2>
                    <p class="mt-1 text-sm text-base-content/70">
                        Explore our collection of interactive sign language signs and notes
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

            <!-- Sign Builder Section -->
            <div class="bg-base-200 p-4 rounded-lg">
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <div class="flex-1 w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Sign Builder</span>
                            <span class="label-text-alt" id="signCount">0 signs</span>
                        </label>
                        <div class="flex gap-2 items-center">
                            <div class="flex-1 min-h-[3rem] p-2 bg-base-100 border border-base-300 rounded-lg flex flex-wrap gap-2 items-center" id="signBuilderContainer">
                                <span class="text-base-content/50 text-sm" id="builderPlaceholder">Add notes to build your sign sequence...</span>
                            </div>
                            <button class="btn btn-primary" id="buildButton" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Build
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @foreach($lessons as $lesson)
                <div class="mb-8 lesson-section" data-lesson-id="{{ $lesson->id }}">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
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
                            {{-- Display Quizzes --}}
                            @php $cardIndex = 0; @endphp
                            @forelse($lesson->quizzes as $quiz)
                                <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 w-full shadow-lg quiz-card {{ $cardIndex >= 5 ? 'hidden-card' : '' }}" 
                                     data-lesson-id="{{ $lesson->id }}" 
                                     data-card-index="{{ $cardIndex }}"
                                     data-type="quiz"
                                     data-searchable="{{ strtolower($quiz->title . ' ' . $quiz->description) }}">
                                    <div class="card-body">
                                        <h2 class="card-title truncate">{{ $quiz->title }}</h2>
                                        <p class="mb-2 truncate">{{ $quiz->description }}</p>
                                        <div class="badge badge-primary badge-sm mb-2">Quiz</div>
                                        <div class="card-actions justify-end">
                                            <a href="{{ route('player.notes.show', $quiz->id) }}" class="btn btn-success btn-circle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @php $cardIndex++; @endphp
                            @empty
                            @endforelse

                            {{-- Display Notes --}}
                            @forelse($lesson->notes as $note)
                                <div class="card bg-base-100 w-full shadow-sm note-card {{ $cardIndex >= 5 ? 'hidden-card' : '' }}" 
                                     data-lesson-id="{{ $lesson->id }}" 
                                     data-card-index="{{ $cardIndex }}"
                                     data-type="notes"
                                     data-searchable="{{ strtolower($note->note_text) }}">
                                    <figure>
                                        @if($note->media_path)
                                            <img src="{{ asset('storage/' . $note->media_path) }}" 
                                                 alt="{{ $note->note_text }}" 
                                                 onerror="this.src='https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp'"/>
                                        @else
                                            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" 
                                                 alt="Default image"/>
                                        @endif
                                    </figure>
                                    <div class="card-body">
                                        <h2 class="card-title">{{ $note->note_text }}</h2>
                                        <p>Sign language note from {{ $lesson->title }}</p>
                                        <div class="badge badge-secondary badge-sm mb-2">Note</div>
                                        <div class="card-actions justify-end">
                                            <button class="btn btn-sm btn-outline preview-note-btn" 
                                                    data-note-id="{{ $note->id }}"
                                                    data-media-path="{{ $note->media_path ? asset('storage/' . $note->media_path) : '' }}"
                                                    data-note-text="{{ $note->note_text }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Preview
                                            </button>
                                            <button class="btn btn-sm btn-primary add-to-builder-btn" 
                                                    data-note-id="{{ $note->id }}"
                                                    data-note-text="{{ $note->note_text }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @php $cardIndex++; @endphp
                            @empty
                            @endforelse

                            {{-- Show "Coming Soon" if no content --}}
                            @if($lesson->quizzes->isEmpty() && $lesson->notes->isEmpty())
                                <div class="card card-border bg-base-200 dark:bg-base-300 w-full shadow-lg col-span-full">
                                    <div class="card-body items-center text-center">
                                        <h2 class="card-title">Coming Soon!</h2>
                                        <p class="mb-2">Thanks for your patience!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Sign Builder Modal --}}
    <dialog id="sign_builder_modal" class="modal">
        <div class="modal-box w-11/12 max-w-4xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Sign Builder Preview</h3>
            
            <div class="flex flex-col items-center gap-4">
                <!-- Navigation Controls -->
                <div class="flex items-center gap-4">
                    <button class="btn btn-circle" id="prevSign" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <div class="text-center">
                        <span class="text-sm text-base-content/70">Sign</span>
                        <div class="font-bold text-lg">
                            <span id="currentSignIndex">1</span> of <span id="totalSigns">0</span>
                        </div>
                    </div>
                    
                    <button class="btn btn-circle" id="nextSign" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <!-- Sign Display -->
                <div class="w-full max-w-md">
                    <div class="card bg-base-100 shadow-lg">
                        <figure class="h-64">
                            <img id="currentSignImage" src="" alt="Current sign" class="w-full h-full object-cover">
                        </figure>
                        <div class="card-body text-center">
                            <h2 class="card-title justify-center" id="currentSignTitle">No signs added</h2>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full max-w-md">
                    <progress class="progress progress-primary w-full" id="signProgress" value="0" max="100"></progress>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- Preview Modal --}}
    <dialog id="preview_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Note Preview</h3>
            <div class="flex flex-col items-center gap-4">
                <img id="preview-image" src="" alt="Note preview" class="max-w-full max-h-96 rounded-lg shadow-lg">
                <div class="text-center">
                    <h4 class="font-semibold text-lg" id="preview-title"></h4>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(function () {
            // Sign Builder Management
            let signBuilderNotes = [];
            let currentSignIndex = 0;

            function updateSignBuilder() {
                const container = $('#signBuilderContainer');
                const placeholder = $('#builderPlaceholder');
                const buildButton = $('#buildButton');
                const signCount = $('#signCount');

                container.empty();
                
                if (signBuilderNotes.length === 0) {
                    container.append(placeholder);
                    buildButton.prop('disabled', true);
                    signCount.text('0 signs');
                } else {
                    placeholder.hide();
                    buildButton.prop('disabled', false);
                    signCount.text(`${signBuilderNotes.length} sign${signBuilderNotes.length !== 1 ? 's' : ''}`);
                    
                    signBuilderNotes.forEach((note, index) => {
                        const pill = $(`
                            <div class="badge badge-primary gap-2 py-3 px-3">
                                <span>${note.text}</span>
                                <button class="btn btn-ghost btn-xs btn-circle remove-sign" data-index="${index}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        `);
                        container.append(pill);
                    });
                }
            }

            function updateSignBuilderModal() {
                const totalSigns = signBuilderNotes.length;
                $('#totalSigns').text(totalSigns);
                
                if (totalSigns === 0) {
                    $('#currentSignIndex').text('0');
                    $('#currentSignImage').attr('src', '');
                    $('#currentSignTitle').text('No signs added');
                    $('#signProgress').val(0);
                    $('#prevSign, #nextSign').prop('disabled', true);
                    return;
                }

                // Ensure currentSignIndex is within bounds
                if (currentSignIndex >= totalSigns) currentSignIndex = totalSigns - 1;
                if (currentSignIndex < 0) currentSignIndex = 0;

                const currentNote = signBuilderNotes[currentSignIndex];
                $('#currentSignIndex').text(currentSignIndex + 1);
                $('#currentSignImage').attr('src', currentNote.mediaPath || 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp');
                $('#currentSignTitle').text(currentNote.text);
                
                // Update progress
                const progress = totalSigns > 1 ? ((currentSignIndex + 1) / totalSigns) * 100 : 100;
                $('#signProgress').val(progress);
                
                // Update navigation buttons
                $('#prevSign').prop('disabled', currentSignIndex === 0);
                $('#nextSign').prop('disabled', currentSignIndex === totalSigns - 1);
            }

            // Add note to sign builder
            function addNoteToBuilder(noteId, noteText, mediaPath) {
                // Generate unique identifier for each instance
                const uniqueId = Date.now() + Math.random();
                
                signBuilderNotes.push({
                    id: noteId,
                    uniqueId: uniqueId,
                    text: noteText,
                    mediaPath: mediaPath
                });
                
                updateSignBuilder();
            }

            // Remove note from sign builder
            $(document).on('click', '.remove-sign', function() {
                const index = parseInt($(this).data('index'));
                signBuilderNotes.splice(index, 1);
                
                // Adjust current sign index if necessary
                if (currentSignIndex >= signBuilderNotes.length && signBuilderNotes.length > 0) {
                    currentSignIndex = signBuilderNotes.length - 1;
                } else if (signBuilderNotes.length === 0) {
                    currentSignIndex = 0;
                }
                
                updateSignBuilder();
                updateSignBuilderModal();
            });

            // Build button click
            $('#buildButton').on('click', function() {
                if (signBuilderNotes.length > 0) {
                    currentSignIndex = 0;
                    updateSignBuilderModal();
                    document.getElementById('sign_builder_modal').showModal();
                }
            });

            // Navigation in sign builder modal
            $('#prevSign').on('click', function() {
                if (currentSignIndex > 0) {
                    currentSignIndex--;
                    updateSignBuilderModal();
                }
            });

            $('#nextSign').on('click', function() {
                if (currentSignIndex < signBuilderNotes.length - 1) {
                    currentSignIndex++;
                    updateSignBuilderModal();
                }
            });

            // Keyboard navigation in modal
            $(document).on('keydown', function(e) {
                if ($('#sign_builder_modal')[0].open) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        $('#prevSign').click();
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        $('#nextSign').click();
                    }
                }
            });

            // Initialize sign builder
            updateSignBuilder();

            // Initialize view all buttons
            $('.lesson-section').each(function () {
                const totalCards = $(this).find('.quiz-card, .note-card').length;
                if (totalCards <= 5) {
                    $(this).find('.view-all-btn').hide();
                }
            });

            // View all / collapse functionality
            $('.view-all-btn').click(function () {
                const lessonId = $(this).data('lesson-id');
                const lesson = $(`.lesson-section[data-lesson-id="${lessonId}"]`);
                const cards = lesson.find('.quiz-card, .note-card');
                const hidden = cards.filter('.hidden-card');
                const textEl = $(this).find('.btn-text');
                const expandIcon = $(this).find('.expand-icon');
                const collapseIcon = $(this).find('.collapse-icon');

                if (hidden.length) {
                    hidden.removeClass('hidden-card').show();
                    textEl.text('Collapse');
                    expandIcon.addClass('hidden');
                    collapseIcon.removeClass('hidden');
                } else {
                    cards.each(function (i) {
                        if (i >= 5) $(this).addClass('hidden-card').hide();
                    });
                    textEl.text('View All');
                    expandIcon.removeClass('hidden');
                    collapseIcon.addClass('hidden');
                }
            });

            // Keyboard shortcut for search
            $(document).on('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    $('#searchInput').focus();
                }
            });

            // Search and filter functionality
            let debounce;
            $('#searchInput').on('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(() => {
                    filterAndSearch();
                }, 300);
            });

            $('#typeFilter').on('change', filterAndSearch);

            function filterAndSearch() {
                const search = $('#searchInput').val().toLowerCase().trim();
                const filter = $('#typeFilter').val();
                let foundAny = false;

                $('.lesson-section').each(function () {
                    let sectionHasMatch = false;

                    $(this).find('.quiz-card, .note-card').each(function () {
                        const searchableText = $(this).data('searchable') || '';
                        const type = $(this).data('type');

                        const matchSearch = !search || searchableText.includes(search);
                        const matchFilter = filter === 'all' || filter === type;

                        if (matchSearch && matchFilter) {
                            $(this).show().removeClass('hidden-card');
                            sectionHasMatch = true;
                            foundAny = true;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (sectionHasMatch) {
                        $(this).show();
                        if (search || filter !== 'all') {
                            $(this).find('.view-all-btn').hide();
                        } else {
                            const totalCards = $(this).find('.quiz-card, .note-card').length;
                            if (totalCards > 5) {
                                $(this).find('.view-all-btn').show();
                            }
                        }
                    } else {
                        $(this).hide();
                    }
                });

                // Show/hide no results message
                $('#no-results-message').remove();
                if (!foundAny && (search || filter !== 'all')) {
                    $('.max-w-7xl').append(`
                        <div id="no-results-message" class="text-center py-8">
                            <div class="flex flex-col items-center gap-4">
                                <div class="text-base-content/70">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold">No content found</h3>
                                <p class="text-base-content/70">Try adjusting your search terms or filter</p>
                            </div>
                        </div>
                    `);
                }
            }

            // Preview note functionality
            $('.preview-note-btn').on('click', function() {
                const mediaPath = $(this).data('media-path');
                const noteText = $(this).data('note-text');
                
                $('#preview-image').attr('src', mediaPath || 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp');
                $('#preview-title').text(noteText);
                
                document.getElementById('preview_modal').showModal();
            });

            // Add to sign builder functionality (updated)
            $('.add-to-builder-btn').on('click', function() {
                const noteId = $(this).data('note-id');
                const noteText = $(this).data('note-text');
                const mediaPath = $(this).closest('.note-card').find('img').attr('src');
                
                addNoteToBuilder(noteId, noteText, mediaPath);
                
                // Visual feedback
                const button = $(this);
                const originalText = button.html();
                button.html(`
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Added
                `).addClass('btn-success').removeClass('btn-primary');
                
                setTimeout(() => {
                    button.html(originalText).removeClass('btn-success').addClass('btn-primary');
                }, 1500);
            });
        });
    </script>
</x-app-layout>