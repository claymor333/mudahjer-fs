<x-app-layout>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

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
                    <p class="mt-1 text-sm text-base-content/70 italic" id="builderPlaceholder">
                        Want to build signs? <a class="link link-hover text-primary font-semibold" onclick="$('#typeFilter').val('notes').trigger('change')">Switch to Notes</a>
                    </p>
                </div>
                <div class="join join-vertical sm:join-horizontal gap-2 w-full sm:w-auto">
                    <div class="join-item flex-1 sm:flex-none">
                        <select class="select select-bordered w-full" id="typeFilter">
                            {{-- <option value="all">All Content</option> --}}
                            <option value="quiz">Quizzes</option>
                            <option value="notes">Notes</option>
                            <option value="alphabetic">Alphabetic</option>
                        </select>
                    </div>
                    <div class="join-item relative flex-1 sm:flex-none">
                        <input type="search" id="searchInput" class="input input-bordered w-full pr-20" placeholder="Search signs..." />
                        <div class="absolute top-0 right-0 join-item h-full flex items-center pr-2">
                            <kbd class="kbd kbd-sm">⌘</kbd>
                            <kbd class="kbd kbd-sm ml-1">K</kbd>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-slot>
    <div id="signBuilderWrapper" class="transition-all duration-500 ease-in-out overflow-hidden sticky top-[4rem] z-10 bg-base-100 border-b border-base-200 shadow max-h-[1000px] opacity-0">
        <!-- Sign Builder Section -->
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center py-2">
            <div class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold">Sign Builder</span>
                        <span class="label-text-alt" id="signCount">1 sign</span>
                    </legend>
                    <div class="flex gap-2 items-center">
                        <div class="flex-1 min-h-[3rem] p-2 bg-base-100 input rounded-lg flex flex-wrap gap-2 items-center" id="signBuilderContainer"><div class="badge badge-primary gap-2 py-3 px-3">
                        <span>2</span>
                        <button class="btn btn-ghost btn-xs btn-circle remove-sign" data-index="0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div></div>
                        <button class="btn btn-primary" id="buildButton">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Build
                        </button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- Regular lesson sections -->
    <div class="py-6" id="regularSections">
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
                                <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 w-full shadow-lg quiz-card {{ $cardIndex >= 5 ? 'hidden-card' : '' }}" data-lesson-id="{{ $lesson->id }}" data-card-index="{{ $cardIndex }}"data-type="quiz" data-searchable-string="{{ strtolower($quiz->title . ' ' . $quiz->description) }}">
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
                                <div class="card card-border border-base-300 bg-base-100 dark:bg-base-200 w-full shadow-lg note-card hidden-card" data-lesson-id="{{ $lesson->id }}" data-card-index="{{ $cardIndex }}" data-type="notes" data-searchable-string="{{ strtolower($note->note_text) }}" data-note-text="{{ $note->note_text }}">
                                    <figure>
                                        @if($note->media_path)
                                            @php
                                                $ext = pathinfo($note->media_path, PATHINFO_EXTENSION);
                                                $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']);
                                            @endphp

                                            @if($isVideo)
                                                <video controls class="w-full h-auto max-h-60 object-contain">
                                                    <source src="{{ asset('storage/' . $note->media_path) }}" type="video/{{ $ext }}">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <img src="{{ asset('storage/' . $note->media_path) }}" alt="{{ $note->note_text }}" class="w-full object-contain max-h-60"/>
                                            @endif
                                        @else
                                            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" alt="Default image"/>
                                        @endif
                                    </figure>


                                    <div class="card-body">
                                        <h2 class="card-title">{{ $note->note_text }}</h2>
                                        <p class="mt-1 text-sm text-base-content/70">{{ $lesson->title }}</p>
                                        <div class="badge badge-secondary badge-sm mt-2 mb-2">Note</div>
                                        <div class="card-actions justify-end">
                                            <button class="btn btn-sm btn-outline preview-note-btn" 
                                                    data-note-id="{{ $note->id }}"
                                                    data-media-path="{{ $note->media_path ? asset('storage/' . $note->media_path) : '' }}"
                                                    data-note-text="{{ $note->note_text }}"
                                                    @if($note->media_path)
                                                    data-media-type="{{ $isVideo ? 'video' : 'image' }}"
                                                    @if($isVideo)
                                                    data-video-type="video/{{ $ext }}"
                                                    @endif
                                                    @endif>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Preview
                                            </button>
                                            <button class="btn btn-sm btn-circle btn-primary add-to-builder-btn" 
                                                    data-note-id="{{ $note->id }}"
                                                    data-note-text="{{ $note->note_text }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @php $cardIndex++; @endphp
                            @empty
                            @endforelse

                            {{-- Show "Coming Soon" if no content --}}
                            {{-- @if($lesson->quizzes->isEmpty() && $lesson->notes->isEmpty())
                                <div class="card card-border bg-base-200 dark:bg-base-300 w-full shadow-lg col-span-full">
                                    <div class="card-body items-center text-center">
                                        <h2 class="card-title">Coming Soon!</h2>
                                        <p class="mb-2">Thanks for your patience!</p>
                                    </div>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Alphabetic sections (initially hidden) -->
    <div class="py-6 hidden" id="alphabeticSections">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- These sections will be dynamically populated -->
        </div>
    </div>

    {{-- Sign Builder Modal --}}
    <dialog id="sign_builder_modal" class="modal">
        <div class="modal-box w-11/12 max-w-4xl my-8">
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
                <div class="w-full flex flex-col items-center gap-4">
                    <div id="signMediaWrapper" class="relative w-full bg-base-200/50 rounded-lg overflow-hidden flex items-center justify-center">
                        <img id="currentSignImage" ...>
                    </div>
                    <div class="text-center">
                        <h2 class="text-xl font-semibold" id="currentSignTitle">No signs added</h2>
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
        <div class="modal-box w-11/12 max-w-2xl my-8">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Note Preview</h3>
            <div class="flex flex-col items-center gap-4">
                <div id="preview-media-wrapper" class="w-full flex justify-center">
                    <img id="preview-image" src="" alt="Note preview" class="max-w-full max-h-96 rounded-lg shadow-lg hidden">
                    <video id="preview-video" controls class="max-w-full max-h-96 rounded-lg shadow-lg hidden">
                        <source src="" type="">
                        Your browser does not support the video tag.
                    </video>
                </div>
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
            let alphabeticData = null; // Cache for alphabetic data

            function updateSignBuilder() {
                const wrapper = $('#signBuilderWrapper');
                const container = $('#signBuilderContainer');
                const buildButton = $('#buildButton');
                const signCount = $('#signCount');

                container.empty();

                if (signBuilderNotes.length === 0) {
                    wrapper.removeClass('max-h-[1000px] opacity-100').addClass('max-h-0 opacity-0');
                    buildButton.prop('disabled', true);
                    signCount.text('0 signs');
                } else {
                    wrapper.removeClass('max-h-0 opacity-0 hidden').addClass('max-h-[1000px] opacity-100');
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
                $('#currentSignTitle').text(currentNote.text);
                const ext = currentNote.mediaPath?.split('.').pop().toLowerCase();
                const isVideo = ['mp4', 'webm', 'ogg'].includes(ext);

                // Create a fresh wrapper to fully reset rendering context
                const newMediaWrapper = $(`
                    <div id="signMediaWrapper" class="relative w-full bg-base-200/50 rounded-lg overflow-hidden flex items-center justify-center"></div>
                `);
                $('#signMediaWrapper').replaceWith(newMediaWrapper);

                if (isVideo) {
                    const video = $(`
                        <video controls class="w-auto max-w-full h-auto max-h-[70vh] object-contain">
                            <source src="${currentNote.mediaPath}" type="video/${ext}">
                            Your browser does not support the video tag.
                        </video>
                    `);
                    newMediaWrapper.append(video);
                } else {
                    const img = $(`
                        <img id="currentSignImage" src="${currentNote.mediaPath || 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp'}"
                            alt="Current sign"
                            class="w-auto max-w-full h-auto max-h-[70vh] object-contain">
                    `);
                    newMediaWrapper.append(img);
                }

                // Update progress
                const progress = totalSigns > 1 ? ((currentSignIndex + 1) / totalSigns) * 100 : 100;
                $('#signProgress').val(progress);
                
                // Update navigation buttons
                $('#prevSign').prop('disabled', currentSignIndex === 0);
                $('#nextSign').prop('disabled', currentSignIndex === totalSigns - 1);
            }

            // Generate alphabetic sections
            function generateAlphabeticSections() {
                if (alphabeticData) return alphabeticData; // Return cached data

                // Collect all notes from all lessons
                const allNotes = [];
                $('.note-card').each(function() {
                    const noteText = $(this).data('note-text');
                    if (noteText) {
                        allNotes.push({
                            element: $(this).clone(),
                            text: noteText.toString().trim()
                        });
                    }
                });

                // Sort notes alphanumerically
                allNotes.sort((a, b) => {
                    return a.text.toLowerCase().localeCompare(b.text.toLowerCase(), undefined, {
                        numeric: true,
                        sensitivity: 'base'
                    });
                });

                // Group notes by first character
                const grouped = {};
                allNotes.forEach(note => {
                    const firstChar = note.text.charAt(0).toUpperCase();
                    let section;
                    
                    if (/[0-9]/.test(firstChar) || !/[A-Z]/.test(firstChar)) {
                        section = '#'; // Numbers and symbols
                    } else {
                        section = firstChar;
                    }
                    
                    if (!grouped[section]) {
                        grouped[section] = [];
                    }
                    grouped[section].push(note);
                });

                // Create sorted sections (# first, then A-Z)
                const sections = {};
                const sortedKeys = Object.keys(grouped).sort((a, b) => {
                    if (a === '#') return -1;
                    if (b === '#') return 1;
                    return a.localeCompare(b);
                });

                sortedKeys.forEach(key => {
                    sections[key] = grouped[key];
                });

                alphabeticData = sections;
                return sections;
            }

            function renderAlphabeticSections() {
                const sections = generateAlphabeticSections();
                const container = $('#alphabeticSections .max-w-7xl');
                container.empty();

                Object.keys(sections).forEach(sectionKey => {
                    const notes = sections[sectionKey];
                    let cardIndex = 0;

                    const sectionElement = $(`
                        <div class="mb-8 alphabetic-section" data-section="${sectionKey}">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold flex items-center gap-2">
                                    ${sectionKey}
                                </h3>
                                <button class="btn btn-ghost btn-sm view-all-btn-alpha" data-section="${sectionKey}" ${notes.length <= 5 ? 'style="display: none;"' : ''}>
                                    <span class="btn-text">View All</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 expand-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 collapse-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="cards-container-alpha" data-section="${sectionKey}">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                </div>
                            </div>
                        </div>
                    `);

                    const grid = sectionElement.find('.grid');
                    notes.forEach(note => {
                        const cardElement = note.element;
                        cardElement.removeClass('hidden-card');
                        cardElement.attr('data-alphabetic-index', cardIndex);
                        
                        if (cardIndex >= 5) {
                            cardElement.addClass('hidden-card');
                        }
                        
                        grid.append(cardElement);
                        cardIndex++;
                    });

                    container.append(sectionElement);
                });

                // Reattach event handlers for cloned elements
                attachAlphabeticEventHandlers();
            }

            function attachAlphabeticEventHandlers() {
                // Preview button handlers for alphabetic sections
                $('#alphabeticSections .preview-note-btn').off('click').on('click', function() {
                    const mediaPath = $(this).data('media-path');
                    const noteText = $(this).data('note-text');
                    const mediaType = $(this).data('media-type') || 'image';
                    const videoType = $(this).data('video-type');
                    
                    // Reset both media elements
                    const $image = $('#preview-image');
                    const $video = $('#preview-video');
                    $image.addClass('hidden');
                    $video.addClass('hidden');
                    
                    if (mediaType === 'video') {
                        // Handle video
                        const $source = $video.find('source');
                        $source.attr('src', mediaPath);
                        $source.attr('type', videoType);
                        $video.removeClass('hidden')[0].load(); // Reload video with new source
                    } else {
                        // Handle image
                        $image
                            .attr('src', mediaPath || 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp')
                            .removeClass('hidden');
                    }
                    
                    $('#preview-title').text(noteText);
                    document.getElementById('preview_modal').showModal();
                });

                // Add to builder button handlers for alphabetic sections
                $('#alphabeticSections .add-to-builder-btn').off('click').on('click', function() {
                    const noteId = $(this).data('note-id');
                    const noteText = $(this).data('note-text');
                    let mediaPath = $(this).closest('.note-card').find('img, video source').attr('src');
                    
                    addNoteToBuilder(noteId, noteText, mediaPath);
                    
                    // Visual feedback
                    const button = $(this);
                    const originalText = button.html();
                    button.html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    `).addClass('btn-success').removeClass('btn-primary');
                    
                    setTimeout(() => {
                        button.html(originalText).removeClass('btn-success').addClass('btn-primary');
                    }, 1500);
                });

                // View all button handlers for alphabetic sections
                $('.view-all-btn-alpha').off('click').on('click', function() {
                    const section = $(this).data('section');
                    const sectionEl = $(`.alphabetic-section[data-section="${section}"]`);
                    const cards = sectionEl.find('.note-card');
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
                    // First show all cards of current type
                    const currentType = $('#typeFilter').val();
                    const typeCards = cards.filter(`[data-type="${currentType}"]`);
                    typeCards.removeClass('hidden-card').show();
                    
                    // Then hide cards beyond index 4 (showing 5 cards)
                    typeCards.each(function (i) {
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

                // Show/hide appropriate sections based on filter
                if (filter === 'alphabetic') {
                    $('#regularSections').hide();
                    $('#alphabeticSections').show();
                    $('#builderPlaceholder').hide();
                    
                    // Generate alphabetic sections if not already done
                    if (!alphabeticData) {
                        renderAlphabeticSections();
                    }
                    
                    // Apply search to alphabetic sections
                    if (search) {
                        $('.alphabetic-section').each(function() {
                            const section = $(this);
                            const cards = section.find('.note-card');
                            let sectionHasMatch = false;
                            
                            cards.each(function() {
                                const searchableText = $(this).attr('data-searchable-string') || '';
                                if (searchableText.includes(search)) {
                                    $(this).removeClass('hidden-card').show();
                                    sectionHasMatch = true;
                                    foundAny = true;
                                } else {
                                    $(this).addClass('hidden-card').hide();
                                }
                            });
                            
                            if (sectionHasMatch) {
                                section.show();
                                section.find('.view-all-btn-alpha').hide(); // Hide view all when searching
                            } else {
                                section.hide();
                            }
                        });
                    } else {
                        // Not searching, show all sections and apply normal limits
                        $('.alphabetic-section').show();
                        $('.alphabetic-section').each(function() {
                            const section = $(this);
                            const cards = section.find('.note-card');
                            
                            cards.each(function(index) {
                                if (index < 5) {
                                    $(this).removeClass('hidden-card').show();
                                } else {
                                    $(this).addClass('hidden-card').hide();
                                }
                            });
                            
                            // Show view all button if more than 5 cards
                            if (cards.length > 5) {
                                section.find('.view-all-btn-alpha').show();
                            }
                            
                            foundAny = true;
                        });
                    }
                } else {
                    // Regular quiz/notes filtering
                    $('#regularSections').show();
                    $('#alphabeticSections').hide();
                    
                    if (filter === 'notes') {
                        $('#builderPlaceholder').hide();
                    } else {
                        $('#builderPlaceholder').show();
                    }

                    // First hide all cards
                    $('.quiz-card, .note-card').hide();

                    $('.lesson-section').each(function () {
                        const currentSection = $(this);
                        let sectionHasMatch = false;
                        
                        // Get all cards that match both search and filter
                        const matchingCards = currentSection.find('.quiz-card, .note-card').filter(function() {
                            const searchableText = $(this).attr('data-searchable-string') || '';
                            const type = $(this).data('type');
                            return (type === filter) && (!search || searchableText.includes(search));
                        });

                        // Only show sections that have matching cards
                        if (matchingCards.length > 0) {
                            currentSection.show();
                            sectionHasMatch = true;
                            foundAny = true;

                            // When searching, show all matching cards
                            if (search) {
                                matchingCards.show();
                                currentSection.find('.view-all-btn').hide();
                            } else {
                                // Not searching, apply 5-card limit
                                matchingCards.each(function(index) {
                                    if (index < 5) {
                                        $(this).removeClass('hidden-card').show();
                                    } else {
                                        $(this).addClass('hidden-card').hide();
                                    }
                                });

                                // Show "View All" button only if there are more than 5 matching cards
                                const viewAllBtn = currentSection.find('.view-all-btn');
                                if (matchingCards.length >= 5) {
                                    viewAllBtn.show();
                                } else {
                                    viewAllBtn.hide();
                                }
                            }
                        }

                        // Hide sections with no matching content
                        if (matchingCards.length === 0) {
                            currentSection.hide();
                        }
                    });
                }

                // Show/hide no results message
                const $existingMessage = $('#no-results-message');
                if (!foundAny) {
                    if (!$existingMessage.length) {
                        // Only append if message doesn't exist
                        const targetContainer = filter === 'alphabetic' ? '#alphabeticSections .max-w-7xl' : '#regularSections .max-w-7xl';
                        $(targetContainer).prepend(`
                            <div id="no-results-message" class="text-center py-8 animate-fadeIn">
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
                } else {
                    $existingMessage.remove();
                }
            }

            // Set initial filter to quiz
            $('#typeFilter').val('quiz');
            filterAndSearch();

            // Preview note functionality
            $('.preview-note-btn').on('click', function() {
                const mediaPath = $(this).data('media-path');
                const noteText = $(this).data('note-text');
                const mediaType = $(this).data('media-type') || 'image';
                const videoType = $(this).data('video-type');
                
                // Reset both media elements
                const $image = $('#preview-image');
                const $video = $('#preview-video');
                $image.addClass('hidden');
                $video.addClass('hidden');
                
                if (mediaType === 'video') {
                    // Handle video
                    const $source = $video.find('source');
                    $source.attr('src', mediaPath);
                    $source.attr('type', videoType);
                    $video.removeClass('hidden')[0].load(); // Reload video with new source
                } else {
                    // Handle image
                    $image
                        .attr('src', mediaPath || 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp')
                        .removeClass('hidden');
                }
                
                $('#preview-title').text(noteText);
                document.getElementById('preview_modal').showModal();
            });

            // Add to sign builder functionality (updated)
            $('.add-to-builder-btn').on('click', function() {
                const noteId = $(this).data('note-id');
                const noteText = $(this).data('note-text');
                let mediaPath = $(this).closest('.note-card').find('img, video source').attr('src');
                
                addNoteToBuilder(noteId, noteText, mediaPath);
                
                // Visual feedback
                const button = $(this);
                const originalText = button.html();
                button.html(`
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                `).addClass('btn-success').removeClass('btn-primary');
                
                setTimeout(() => {
                    button.html(originalText).removeClass('btn-success').addClass('btn-primary');
                }, 1500);
            });
        });
    </script>
</x-app-layout>