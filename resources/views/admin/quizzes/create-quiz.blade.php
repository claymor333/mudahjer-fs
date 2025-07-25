<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Create Quiz') }}
        </h2>
    </x-slot>

    @vite(['resources/css/quiz-wizard.css'])

    <div class="py-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Enhanced Stepper -->
        <div class="mb-12">
            <div class="grid grid-cols-[200px_1fr_200px] items-center gap-4">
                <!-- Left side: Title when on step 2 -->
                <div class="text-left">
                    <h2 class="text-2xl font-bold" id="step-title">Quiz Information</h2>
                </div>

                <!-- Center: Stepper -->
                <div class="flex justify-center">
                    <ul class="steps w-full">
                        <li class="step step-primary" id="step-1">Quiz Details</li>
                        <li class="step" id="step-2">Quiz Notes</li>
                        <li class="step" id="step-3">Questions</li>
                    </ul>
                </div>

                <!-- Right side: Back button when on step 2 -->
                <div class="text-right">
                    <div class="join" id="step-control-join">
                        <button type="button" class="btn btn-outline btn-sm join-item" onclick="prevStep()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </button>
                        
                        <button type="button" class="btn btn-outline btn-sm join-item" onclick="nextStep()">
                            Next
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l7-7 l-7 -7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <form id="quizForm" class="space-y-6">
            <!-- Step 1: Quiz Details -->
            <div id="quiz-details" class="card bg-base-200 shadow-lg">
                <div class="card-body">
                    
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Quiz Title</span>
                        </legend>
                        <input type="text" id="quiz-title" class="w-full input input-bordered validator" placeholder="Enter quiz title..." required />
                        <p id="quiz-title-val" class="text-error hidden">Title is required.</p>

                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Description</span>
                        </legend>
                        <textarea id="quiz-description" class="w-full textarea textarea-bordered validator" rows="4" placeholder="Describe your quiz..." required></textarea>
                        <p id="quiz-description-val" class="text-error hidden">Description is required.</p>

                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Choices Type</span>
                        </legend>
                        <select id="quiz-choices-type" class="w-full select select-bordered" required>
                            <option value="text" selected>Questions with Text Choices</option>
                            <option value="media">Questions with Media Choices</option>
                        </select>

                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Associated Lesson</span>
                        </legend>
                        <div class="join w-full">
                            <select id="quiz-lesson" class="select select-bordered join-item w-full" required>
                                <option value="" disabled selected>Select a lesson</option>
                                @foreach($lessons as $lesson)
                                    <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary join-item" onclick="createNewLesson()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <p id="quiz-lesson-val" class="text-error hidden">Please select a lesson.</p>
                    </fieldset>
                </div>
            </div>

            <!-- Step 2: Notes -->
            <div id="notes-step" class="hidden">
                <!-- Note Counter -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center bg-base-200 rounded-full px-4 py-2">
                        <span class="text-sm">Note </span>
                        <span id="current-note-num" class="font-bold text-primary mx-1">1</span>
                        <span class="text-sm">of </span>
                        <span id="total-notes" class="font-bold text-primary ml-1">1</span>
                    </div>
                </div>

                <!-- Notes Container with Navigation -->
                <div class="relative">
                    <!-- Notes Carousel -->
                    <div class="notes-carousel" id="notes-carousel">
                    </div>
                    
                    <!-- Navigation Buttons - Fixed on sides -->
                    <div class="fixed left-4 top-1/2 flex flex-col items-center gap-2">
                        <button type="button" class="note-nav prev btn btn-circle btn-ghost hidden" onclick="prevNote()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div class="kbd-inst-q">
                            <span class="flex-row" id="prevKbdN">
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">Q</kbd>
                            </span>
                        </div>
                    </div>
                    
                    <div class="fixed right-4 top-1/2 flex flex-col items-center gap-2">
                        <button type="button" class="note-nav next btn btn-circle btn-ghost hidden" onclick="nextNote()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div class="kbd-inst-q">
                            <span class="flex-row" id="nextKbdN">
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">E</kbd>
                            </span>
                        </div>
                    </div>
                </div>

                @include('admin.quizzes.partials.wizard-fab')
            </div>

            <!-- Step 3: Questions -->
            <div id="questions-step" class="hidden">
                <!-- Question Counter -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center bg-base-200 rounded-full px-4 py-2">
                        <span class="text-sm">Question </span>
                        <span id="current-question-num" class="font-bold text-primary mx-1">1</span>
                        <span class="text-sm">of </span>
                        <span id="total-questions" class="font-bold text-primary ml-1">1</span>
                    </div>
                </div>

                <!-- Questions Container with Navigation -->
                <div class="relative">
                    <!-- Questions Carousel -->
                    <div class="questions-carousel" id="questions-carousel">
                    </div>
                    
                    <!-- Navigation Buttons - Fixed on sides -->
                    <div class="fixed left-4 top-1/2 flex flex-col items-center gap-2">
                        <button type="button" class="question-nav prev btn btn-circle btn-ghost hidden" onclick="prevQuestion()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div class="kbd-inst-q">
                            <span class="flex-row" id="prevKbdQ">
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">Q</kbd>
                            </span>
                        </div>
                    </div>
                    
                    <div class="fixed right-4 top-1/2 flex flex-col items-center gap-2">
                        <button type="button" class="question-nav next btn btn-circle btn-ghost hidden" onclick="nextQuestion()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div class="kbd-inst-q">
                            <span class="flex-row" id="nextKbdQ">
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">E</kbd>
                            </span>
                        </div>
                    </div>
                </div>

                @include('admin.quizzes.partials.wizard-fab')
            </div>
        </form>
    </div>

    <!-- Media Preview Modal -->
    <div class="preview-modal" id="mediaPreviewModal">
        <div class="content">
            <button class="btn btn-circle btn-error absolute top-2 right-2 close-btn" onclick="closeMediaPreview()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img src="" id="previewImage" class="hidden" alt="Media Preview">
            <video controls id="previewVideo" class="hidden" alt="Media Preview">
                <source src="" id="previewVideoSource">
            </video>
        </div>
    </div>

    <!-- Modal for full-size media preview -->
    <div class="preview-modal" id="preview-modal">
        <div class="content">
            <button class="btn btn-circle close-btn" onclick="closePreviewModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div id="preview-content"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentStep = 1;
        let currentQuestionIndex = 0;
        let currentNoteIndex = 0;
        let questions = [];
        let notes = [];
        let currentTheme = 'dark';
        let keyboardNavigationEnabled = true;

        function toggleTheme() {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
        }

        function handleChoiceMediaPreview(input) {
            const $input = $(input);
            const file = input.files[0];
            if (!file) return;

            const $imgBadge = $input.siblings('.choice-preview-img');
            const $videoBadge = $input.siblings('.choice-preview-video');
            const previewImg = $imgBadge.find('img')[0];
            const videoIcon = $videoBadge.find('svg')[0];

            // Reset both badges
            $imgBadge.addClass('hidden');
            $videoBadge.addClass('hidden');

            const reader = new FileReader();

            if (file.type.startsWith('image/')) {
                reader.onload = function (e) {
                    $(previewImg)
                        .attr('src', e.target.result)
                        .off('click')
                        .on('click', function () {
                            showPreviewModal(previewImg);
                        });
                    $imgBadge.removeClass('hidden');
                };
                reader.readAsDataURL(file);

            } else if (file.type.startsWith('video/')) {
                reader.onload = function (e) {
                    $(videoIcon)
                        .off('click')
                        .on('click', function () {
                            const video = document.createElement('video');
                            video.src = e.target.result;
                            video.controls = true;
                            showPreviewModal(video);
                        });
                    $videoBadge.removeClass('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function nextStep() {
            // Step 1 validation (Quiz Details)
            if (currentStep === 1) {

                if (!validateStep(currentStep)) {
                    return;
                }

                currentStep = 2;

                // Update DaisyUI stepper
                $('#step-2').addClass('step-primary');
                $('#quiz-details').addClass('hidden');
                $('#notes-step').removeClass('hidden');
                $('#step-title').text('Quiz Notes');

                if (notes.length === 0) {
                    addNote();
                }

                return; 
            } 
            
            if (currentStep === 2) {

                if (!validateStep(currentStep)) {
                    return;
                }

                currentStep = 3;

                // Update DaisyUI stepper
                $('#step-3').addClass('step-primary');
                $('#notes-step').addClass('hidden');
                $('#questions-step').removeClass('hidden');
                $('#step-title').text('Quiz Questions');

                if (questions.length === 0) {
                    addQuestion();
                }

                return;
            }
        }

        function prevStep() {
            if (currentStep === 2) {
                // Move back to step 1
                $('#step-2').removeClass('step-primary');
                $('#step-1').addClass('step-primary');

                // Show quiz details, hide notes
                $('#notes-step').addClass('hidden');
                $('#quiz-details').removeClass('hidden');

                // Update title & hide back button
                $('#step-title').text('Quiz Information');

                currentStep = 1;
            } else if (currentStep === 3) {
                // Move back to step 2
                $('#step-3').removeClass('step-primary');
                $('#step-2').addClass('step-primary');

                // Show notes step, hide questions
                $('#questions-step').addClass('hidden');
                $('#notes-step').removeClass('hidden');

                // Update title & show back button
                $('#step-title').text('Quiz Notes');

                currentStep = 2;
            }
        }

        function removeNote(noteId) {
            const noteIndex = notes.indexOf(noteId);
            if (noteIndex === -1) return;
            
            if (notes.length <= 1) {
                alert('You must have at least one note!');
                return;
            }

            $(`#${noteId}`).remove();
            notes.splice(noteIndex, 1);
            
            // Update sequence numbers for all notes
            notes.forEach((nId, index) => {
                const noteCard = $(`#${nId}`);
                const newSequence = index + 1;
                noteCard
                    .attr('data-sequence', newSequence)
                    .find('h3')
                    .text(`Note ${newSequence}`);
            });
            
            if (currentNoteIndex >= notes.length) {
                currentNoteIndex = notes.length - 1;
            }
            
            navigateToNote(currentNoteIndex);
            updateNoteCounter();
            updateNavigationButtons();
        }

        function addQuestionOrNote() {
            if (currentStep === 2) {
                addNote();
            } else if (currentStep === 3) {
                addQuestion();
            }
        }

        function addNote() {
            const nextNumber = notes.length + 1;
            const noteId = `note-${Date.now()}`; // Use timestamp instead of counter
            
            const noteHtml = `
                <div class="note-card card bg-base-200 shadow-lg" id="${noteId}" data-sequence="${nextNumber}">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Note ${nextNumber}</h3>
                            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeNote('${noteId}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Note Text</span>
                            </legend>
                            <input type="text" id="note-text-${noteId}" class="w-full input input-bordered validator" placeholder="Enter your note..." required>
                            <p id="note-text-val-${noteId}" class="text-error hidden">Note text is required.</p>

                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Media</span>
                            </legend>
                            <input type="file" id="note-media-${noteId}" class="w-full file-input file-input-bordered validator" accept="image/*,video/*" onchange="previewMedia(this, '${noteId}')" required>
                            <p id="note-media-val-${noteId}" class="text-error hidden">Media file is required.</p>
                            <div class="media-preview mt-4" id="media-preview-${noteId}"></div>
                        </fieldset>
                    </div>
                </div>
            `;

            $('#notes-carousel').append(noteHtml);
            notes.push(noteId);
            
            navigateToNote(notes.length - 1);
            updateNoteCounter();
            updateNavigationButtons();
        }

        function addQuestion() {
            const nextNumber = questions.length + 1;
            const questionId = `question-${Date.now()}`; // unique ID
            const choicesType = $('#quiz-choices-type').val();

            let choiceInputHtml;
            if (choicesType === 'media') {
                    choiceInputHtml = `
                        <div class="indicator w-full relative">
                            <!-- Image preview badge -->
                            <span class="indicator-item choice-media choice-preview-img !p-0 badge badge-secondary w-8 h-8 overflow-hidden hidden">
                                <img src="" alt="preview" class="w-full h-full object-cover cursor-pointer" onclick="showPreviewModal(this)">
                            </span>

                            <!-- Video icon badge -->
                            <span class="indicator-item choice-media choice-preview-video !p-0 badge badge-secondary w-8 h-8 flex items-center justify-center hidden cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20" onclick="showPreviewModal(this)">
                                    <path d="M4.5 3.5a1 1 0 011.6-.8l10 7a1 1 0 010 1.6l-10 7A1 1 0 014.5 17V3.5z" />
                                </svg>
                            </span>

                            <input type="file" 
                                class="file-input file-input-bordered flex-1 validator choice-media-input" 
                                accept="image/*,video/*" 
                                onchange="handleChoiceMediaPreview(this)" 
                                required>
                        </div>
                    `;
            } else {
                choiceInputHtml = `
                    <input type="text" class="input input-bordered flex-1 validator" placeholder="Choice text" required>
                `;
            }

            const questionHtml = `
                <div class="question-card card bg-base-200 shadow-lg" id="${questionId}" data-sequence="${nextNumber}">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Question ${nextNumber}</h3>
                            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeQuestion('${questionId}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend"><span class="font-medium text-lg">Question Text</span></legend>
                            <input type="text" id="question-text-${questionId}" class="w-full input input-bordered validator" placeholder="Enter question text..." required>
                            <p id="question-text-val-${questionId}" class="hidden text-error text-xs mt-1">Question text is required.</p>

                            <legend class="fieldset-legend mt-4"><span class="font-medium text-lg">Media (Optional)</span></legend>
                            <input type="file" id="question-media-${questionId}" class="w-full file-input file-input-bordered"
                                accept="image/*,video/*" onchange="previewMedia(this, '${questionId}')">
                            <div class="media-preview mt-4" id="media-preview-${questionId}"></div>

                            <legend class="fieldset-legend mt-4"><span class="font-medium text-lg">Answer Choices</span></legend>
                            <p id="choice-radio-val-${questionId}" class="text-error hidden text-xs mb-2">
                                Select a correct answer.
                            </p>
                            <div class="choices-container space-y-3" id="choices-${questionId}">
                                <div class="choice-item flex-col p-3 bg-base-100 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="correct-${questionId}" class="radio radio-primary" value="0">
                                        ${choiceInputHtml}
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50"
                                            onclick="removeChoice(this)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="choice-validator-input text-error hidden text-xs mt-1"></p>
                                </div>

                                <div class="choice-item flex-col p-3 bg-base-100 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="correct-${questionId}" class="radio radio-primary" value="1">
                                        ${choiceInputHtml}
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50"
                                            onclick="removeChoice(this)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="choice-validator-input text-error hidden text-xs mt-1"></p>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline btn-primary mt-3" onclick="addChoice('${questionId}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Choice
                            </button>
                        </fieldset>
                    </div>
                </div>
            `;

            $('#questions-carousel').append(questionHtml);
            questions.push(questionId);

            navigateToQuestion(questions.length - 1);
            updateQuestionCounter();
            updateNavigationButtons();
        }

        function updateQuestionsForChoicesType(newType) {
            questions.forEach(questionId => {
                const $questionCard = $(`#${questionId}`);

                // Preserve question text & media
                const questionText = $questionCard.find(`#question-text-${questionId}`).val();
                const mediaInput = $questionCard.find(`#question-media-${questionId}`)[0];
                const mediaFile = mediaInput?.files?.[0] ?? null;

                // Create new choice HTML based on type
                let choiceInputHtml;
                if (newType === 'media') {
                    choiceInputHtml = `
                        <div class="indicator w-full relative">
                            <!-- Image preview badge -->
                            <span class="indicator-item choice-media choice-preview-img !p-0 badge badge-secondary w-8 h-8 overflow-hidden hidden">
                                <img src="" alt="preview" class="w-full h-full object-cover cursor-pointer" onclick="showPreviewModal(this)">
                            </span>

                            <!-- Video icon badge -->
                            <span class="indicator-item choice-media choice-preview-video !p-0 badge badge-secondary w-8 h-8 flex items-center justify-center hidden cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20" onclick="showPreviewModal(this)">
                                    <path d="M4.5 3.5a1 1 0 011.6-.8l10 7a1 1 0 010 1.6l-10 7A1 1 0 014.5 17V3.5z" />
                                </svg>
                            </span>

                            <input type="file" 
                                class="file-input file-input-bordered flex-1 validator choice-media-input" 
                                accept="image/*,video/*" 
                                onchange="handleChoiceMediaPreview(this)" 
                                required>
                        </div>
                    `;
                } else {
                    choiceInputHtml = `
                        <input type="text" class="input input-bordered flex-1 validator" placeholder="Choice text" required>
                    `;
                }

                // Replace the choices container content
                const $choicesContainer = $questionCard.find(`#choices-${questionId}`);
                $choicesContainer.empty();

                // Recreate two default choices
                for (let i = 0; i < 2; i++) {
                    const choiceHtml = `
                        <div class="choice-item flex-col p-3 bg-base-100 rounded-lg">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="correct-${questionId}" class="radio radio-primary" value="${i}">
                                ${choiceInputHtml}
                                <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50" onclick="removeChoice(this)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="choice-validator-input text-error hidden text-xs mt-1"></p>
                        </div>
                    `;
                    $choicesContainer.append(choiceHtml);
                }
            });
        }

        $(document).on('change', '#quiz-choices-type', function () {
            const newType = $(this).val();
            updateQuestionsForChoicesType(newType);
        });

        $(document).on('change', '.choice-media-input', function () {
            const fileInput = this;
            const file = fileInput.files[0];
            const $container = $(fileInput).closest('.indicator');
            const $previewBadge = $container.find('.preview-badge');
            const $img = $previewBadge.find('img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $img.attr('src', e.target.result);
                    $previewBadge.show();
                };
                reader.readAsDataURL(file);
            } else {
                $img.attr('src', '');
                $previewBadge.hide();
            }
        });

        $(document).on('change', '#quiz-lesson', function () {
            const $lesson = $('#quiz-lesson');

            // optionally apply success color
            $lesson.css('--input-color', 'var(--color-success)');
        });

        function removeQuestion(questionId) {
            const questionIndex = questions.indexOf(questionId);
            if (questionIndex === -1) return;
            
            if (questions.length <= 1) {
                alert('You must have at least one question!');
                return;
            }

            $(`#${questionId}`).remove();
            questions.splice(questionIndex, 1);
            
            // Update sequence numbers for all questions
            questions.forEach((qId, index) => {
                const questionCard = $(`#${qId}`);
                const newSequence = index + 1;
                questionCard
                    .attr('data-sequence', newSequence)
                    .find('h3')
                    .text(`Question ${newSequence}`);
            });
            
            if (currentQuestionIndex >= questions.length) {
                currentQuestionIndex = questions.length - 1;
            }
            
            navigateToQuestion(currentQuestionIndex);
            updateQuestionCounter();
            updateNavigationButtons();
        }

        function addChoice(questionId) {
            const choicesContainer = $(`#choices-${questionId}`);
            const choiceCount = choicesContainer.children().length;
            
            const choiceHtml = `
                <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                    <input type="radio" name="correct-${questionId}" class="radio radio-primary" value="${choiceCount}">
                    <input type="text" class="input input-bordered flex-1 validator" placeholder="Choice ${choiceCount + 1}" required>
                    <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeChoice(this)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            const $newChoice = $(choiceHtml);
            choicesContainer.append($newChoice);
            
            // Focus the text input of the new choice
            const $textInput = $newChoice.find('input[type="text"]');
            $textInput.focus();
            
            // Get the current scroll position and the height of the new choice
            const currentScroll = window.scrollY;
            const choiceHeight = $newChoice.outerHeight();
            
            // Scroll down by the height of the new choice plus the gap
            window.scrollTo({
                top: currentScroll + choiceHeight + 12, // 12px for the gap between choices (space-y-3)
                behavior: 'smooth'
            });
            
            updateChoiceRemoveButtons(questionId);
        }

        function removeChoice(button) {
            const choiceItem = $(button).closest('.choice-item');
            const questionCard = choiceItem.closest('.question-card');
            const questionId = questionCard.attr('id');
            const choicesContainer = questionCard.find('.choices-container');
            
            if (choicesContainer.children().length <= 2) {
                return;
            }

            // Get the height of the choice being removed
            const choiceHeight = choiceItem.outerHeight();
            const currentScroll = window.scrollY;
            
            choiceItem.remove();
            updateChoiceIndexes(questionId);
            updateChoiceRemoveButtons(questionId);

            // Scroll up by the height of the removed choice
            window.scrollTo({
                top: currentScroll - choiceHeight,
                behavior: 'smooth'
            });
        }

        function updateChoiceIndexes(questionId) {
            const choicesContainer = $(`#choices-${questionId}`);
            choicesContainer.children().each(function(index) {
                $(this).find('input[type="radio"]').val(index);
                $(this).find('input[type="text"]').attr('placeholder', `Choice ${index + 1}`);
            });
        }

        function validateStep(step) {
            let isValid = true;
            if (step === 1) {
                // Validate Title
                const title = $('#quiz-title').val().trim();
                if (!title) {
                    $('#quiz-title').addClass('input-error');
                    $('#quiz-title-val').removeClass('hidden');
                    isValid = false;
                } else {
                    $('#quiz-title').removeClass('input-error');
                    $('#quiz-title-val').addClass('hidden');
                }

                // Validate Description
                const description = $('#quiz-description').val().trim();
                if (!description) {
                    $('#quiz-description').addClass('input-error');
                    $('#quiz-description-val').removeClass('hidden');
                    isValid = false;
                } else {
                    $('#quiz-description').removeClass('input-error');
                    $('#quiz-description-val').addClass('hidden');
                }

                // Validate Lesson
                const lessonVal = $('#quiz-lesson').val();
                if (!lessonVal) {
                    $('#quiz-lesson').addClass('input-error');
                    $('#quiz-lesson-val').removeClass('hidden');
                    isValid = false;
                } else {
                    $('#quiz-lesson').removeClass('input-error');
                    $('#quiz-lesson-val').addClass('hidden');
                }
            } else if (step === 2) {
                console.log(step, isValid)
                // Validate notes
                notes.forEach((noteId) => {
                    const noteText = $(`#note-text-${noteId}`).val().trim();
                    const noteMedia = $(`#note-media-${noteId}`)[0].files[0];
                    
                    if (!noteText) {
                        $(`#note-text-${noteId}`).addClass('input-error');
                        $(`#note-text-val-${noteId}`).removeClass('hidden');
                        isValid = false;
                    } else {
                        $(`#note-text-${noteId}`).removeClass('input-error');
                        $(`#note-text-val-${noteId}`).addClass('hidden');
                    }
                    
                    if (!noteMedia) {
                        $(`#note-media-${noteId}`).addClass('input-error');
                        $(`#note-media-val-${noteId}`).removeClass('hidden');
                        isValid = false;
                    } else {
                        $(`#note-media-${noteId}`).removeClass('input-error');
                        $(`#note-media-val-${noteId}`).addClass('hidden');
                    }
                });
            } else if (step === 3) {
                questions.forEach((questionId) => {
                    const questionText = $(`#question-text-${questionId}`).val().trim();
                    const choicesType = $('#quiz-choices-type').val();
                    const $questionRadioValidator = $(`#choice-radio-val-${questionId}`);

                    let hasCorrectAnswer = $(`input[name="correct-${questionId}"]:checked`).length > 0;
                    let hasEmptyChoice = false;

                    // Validate question text
                    if (!questionText) {
                        $(`#question-text-${questionId}`).addClass('input-error');
                        $(`#question-text-val-${questionId}`).removeClass('hidden');
                    isValid = false;
                    } else {
                        $(`#question-text-${questionId}`).removeClass('input-error');
                        $(`#question-text-val-${questionId}`).addClass('hidden');
                    }

                    // Validate choices
                    $(`#choices-${questionId} .choice-item`).each(function () {
                        const $choiceItem = $(this);
                        const $choiceValidatorInput = $choiceItem.find('.choice-validator-input');
                        const $radioInput = $choiceItem.find('input[type="radio"]');

                        if (choicesType === 'media') {
                            const fileInput = $choiceItem.find('input[type="file"]')[0];
                            if (!fileInput || !fileInput.files[0]) {
                                $choiceValidatorInput.text('Please select a file.').removeClass('hidden');
                                $(fileInput).addClass('input-error');
                                hasEmptyChoice = true;
                            } else {
                                $choiceValidatorInput.addClass('hidden');
                                $(fileInput).removeClass('input-error');
                            }
                        } else {
                            const textInput = $choiceItem.find('input[type="text"]');
                            if (!textInput.val().trim()) {
                                textInput.addClass('input-error');
                                $choiceValidatorInput.text('This choice cannot be empty.').removeClass('hidden');
                                hasEmptyChoice = true;
                            } else {
                                textInput.removeClass('input-error');
                                $choiceValidatorInput.addClass('hidden');
                            }
                        }

                        // Optional: highlight individual radios
                        if (!$radioInput.is(':checked')) {
                            $radioInput.addClass('input-error').removeClass('radio-primary');
                        } else {
                            $radioInput.removeClass('input-error').addClass('radio-primary');
                        }
                    });

                    // Validate radio selection (only one message per question)
                    if (!hasCorrectAnswer) {
                        $questionRadioValidator.removeClass('hidden');
                        isValid = false;
                    } else {
                        $questionRadioValidator.addClass('hidden');
                    }

                    if (hasEmptyChoice) {
                        isValid = false;
                    }
                });
            }
            return isValid;
        }

        function updateChoiceRemoveButtons(questionId) {
            const choicesContainer = $(`#choices-${questionId}`);
            const removeButtons = choicesContainer.find('button');
            
            if (choicesContainer.children().length > 2) {
                removeButtons.removeClass('opacity-50');
            } else {
                removeButtons.addClass('opacity-50');
            }
        }

        function navigateToQuestion(index) {
            if (index < 0 || index >= questions.length) return;
            
            currentQuestionIndex = index;
            
            questions.forEach((questionId, i) => {
                const questionCard = $(`#${questionId}`);
                const diff = i - index;
                
                questionCard.removeClass('prev-2 prev-1 active next-1 next-2');
                
                if (diff === -2) questionCard.addClass('prev-2');
                else if (diff === -1) questionCard.addClass('prev-1');
                else if (diff === 0) questionCard.addClass('active');
                else if (diff === 1) questionCard.addClass('next-1');
                else if (diff === 2) questionCard.addClass('next-2');
            });
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                navigateToQuestion(currentQuestionIndex - 1);
                updateQuestionCounter();
                updateNavigationButtons();
            }
        }

        function nextQuestion() {
			if (currentQuestionIndex < questions.length - 1) {
				// normal: go to next question
				navigateToQuestion(currentQuestionIndex + 1);
			} else {
				// already at the last question → create a new one
				addQuestion();
			}
			updateQuestionCounter();
			updateNavigationButtons();
		}

        function navigateToNote(index) {
            if (index < 0 || index >= notes.length) return;
            
            currentNoteIndex = index;
            
            notes.forEach((noteId, i) => {
                const noteCard = $(`#${noteId}`);
                const diff = i - index;
                
                noteCard.removeClass('prev-2 prev-1 active next-1 next-2');
                
                if (diff === -2) noteCard.addClass('prev-2');
                else if (diff === -1) noteCard.addClass('prev-1');
                else if (diff === 0) noteCard.addClass('active');
                else if (diff === 1) noteCard.addClass('next-1');
                else if (diff === 2) noteCard.addClass('next-2');
            });
        }

        function prevNote() {
            if (currentNoteIndex > 0) {
                navigateToNote(currentNoteIndex - 1);
                updateNoteCounter();
                updateNavigationButtons();
            }
        }

        function nextNote() {
			if (currentNoteIndex < notes.length - 1) {
				// normal: go to next note
				navigateToNote(currentNoteIndex + 1);
			} else {
				// already at the last note → create a new one
				addNote();
			}
			updateNoteCounter();
			updateNavigationButtons();
		}

        function toggleKeyboardNavigation() {
            keyboardNavigationEnabled = !keyboardNavigationEnabled;
            const btn = $('#toggleKeyboardNav');
            if (keyboardNavigationEnabled) {
                btn.removeClass('btn-ghost').addClass('btn-accent');
                $('.kbd').removeClass('hidden');
                $('.kbd-inst').removeClass('hidden');
                $('.kbd-inst-q').removeClass('hidden');
                $('.kbd-inst').addClass('mt-2');
            } else {
                btn.removeClass('btn-accent').addClass('btn-ghost');
                $('.kbd').addClass('hidden');
                $('.kbd-inst').addClass('hidden');
                $('.kbd-inst-q').addClass('hidden');
                $('.kbd-inst').removeClass('mt-2');
            }
        }

		document.addEventListener('keydown', (e) => {
            if (!keyboardNavigationEnabled) return;
            
            if (e.ctrlKey && (e.key === 'q' || e.key === 'Q')) {
                e.preventDefault();
                if (currentStep === 2 && currentNoteIndex === 0) {
                    prevStep();
                } else if (currentStep === 2) {
                    prevNote();
                } else if (currentStep === 3 && currentQuestionIndex === 0) {
                    prevStep();
                } else if (currentStep === 3) {
                    prevNote();
                } 
            }
            if (e.ctrlKey && (e.key === 'e' || e.key === 'E')) {
                e.preventDefault();
                if (currentStep === 1) {
                    nextStep();
                } else if (currentStep === 2) {
                    nextNote();
                }
                else if (currentStep === 3) {
                    nextQuestion();
                }
            }
            if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
                saveQuiz();
            }
        });

        function updateQuestionCounter() {
            $('#current-question-num').text(currentQuestionIndex + 1);
            $('#total-questions').text(questions.length);
        }

        function updateNoteCounter() {
            $('#current-note-num').text(currentNoteIndex + 1);
            $('#total-notes').text(notes.length);
        }

        function updateNavigationButtons() {
			const prevNBtn = $('.note-nav.prev');
			const nextNBtn = $('.note-nav.next');

            const prevQBtn = $('.question-nav.prev');
			const nextQBtn = $('.question-nav.next');

			const prevKbdN = $('#prevKbdN');
			const nextKbdN = $('#nextKbdN');

            const prevKbdQ = $('#prevKbdQ');
			const nextKbdQ = $('#nextKbdQ');

            if (notes.length <= 1) {
				prevNBtn.addClass('hidden');
				nextNBtn.addClass('hidden');

				prevKbdN.addClass('hidden');
				nextKbdN.addClass('hidden');
			} else {
				prevNBtn.removeClass('hidden');
				nextNBtn.removeClass('hidden');

				prevKbdN.removeClass('hidden');
				nextKbdN.removeClass('hidden');

				// disable prev if at first question/note
				prevNBtn.prop('disabled', currentNoteIndex === 0);

				// next is never disabled, because pressing it creates a new question if needed
				nextNBtn.prop('disabled', false);
			}

			if (questions.length <= 1) {
                prevQBtn.addClass('hidden');
				nextQBtn.addClass('hidden');

				prevKbdQ.addClass('hidden');
				nextKbdQ.addClass('hidden');
			} else {
                prevQBtn.removeClass('hidden');
				nextQBtn.removeClass('hidden');

				prevKbdQ.removeClass('hidden');
				nextKbdQ.removeClass('hidden');

				// disable prev if at first question/note
				prevQBtn.prop('disabled', currentQuestionIndex === 0);

				// next is never disabled, because pressing it creates a new question if needed
				nextQBtn.prop('disabled', false);
			}
		}


        function previewMedia(input, elementId) {
            const preview = $(`#media-preview-${elementId}`);
            preview.empty();

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const isVideo = file.type.startsWith('video/');
                const url = URL.createObjectURL(file);
                
                const mediaContainer = $('<div class="media-preview relative"></div>');
                
                if (isVideo) {
                    const video = $(`<video controls class="w-full max-w-md h-64 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
                        <source src="${url}" type="${file.type}">
                    </video>`);
                    mediaContainer.append(video);
                } else {
                    const img = $(`<img src="${url}" class="w-full max-w-md max-h-[70vh] object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">`);
                    mediaContainer.append(img);
                }
                
                const removeBtn = $(`
                    <button type="button" class="btn btn-sm btn-circle btn-error absolute top-2 right-2" onclick="removeMedia('${elementId}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `);
                
                mediaContainer.append(removeBtn);
                preview.append(mediaContainer);
            }
        }

        function showPreviewModal(element) {
            const modal = $('#preview-modal');
            const content = $('#preview-content');
            content.empty();

            if (element.tagName.toLowerCase() === 'video') {
                // Clone video with all its sources
                const video = $(element).clone();
                video.removeClass('object-cover').addClass('object-contain');
                video[0].currentTime = element.currentTime; // Sync video position
                content.append(video);
            } else {
                // Clone image
                const img = $(element).clone();
                img.removeClass('object-cover').addClass('object-contain');
                content.append(img);
            }

            modal.addClass('active');

            // Close on escape key
            $(document).on('keydown.preview', function(e) {
                if (e.key === 'Escape') {
                    closePreviewModal();
                }
            });

            // Close on click outside content
            modal.on('click.preview', function(e) {
                if ($(e.target).closest('.content').length === 0) {
                    closePreviewModal();
                }
            });
        }

        function closePreviewModal() {
            const modal = $('#preview-modal');
            modal.removeClass('active');
            
            // Clean up event handlers
            $(document).off('keydown.preview');
            modal.off('click.preview');
        }

        function removeMedia(questionId) {
            const preview = $(`#media-preview-${questionId}`);
            const fileInput = $(`#${questionId}`).find('input[type="file"]');
            
            preview.empty();
            fileInput.val('');
        }

        function cancelQuiz() {
            if (confirm('Are you sure you want to cancel? All progress will be lost.')) {
                window.location.href = '/admin/quizzes';
            }
        }

        function createNewLesson() {
            // Create modal for new lesson
            const modalHtml = `
                <dialog id="new-lesson-modal" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg mb-4">Create New Lesson</h3>
                        <form id="new-lesson-form" method="dialog">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">
                                    <span class="label-text font-medium">Lesson Title</span>
                                </legend>
                                <input type="text" id="lesson-title" class="w-full input input-bordered validator" placeholder="Enter lesson title..." required />
                                <p class="validator-hint hidden">Title is required.</p>

                                <legend class="fieldset-legend">
                                    <span class="label-text font-medium">Description</span>
                                </legend>
                                <textarea id="lesson-description" class="w-full textarea textarea-bordered validator" rows="4" placeholder="Describe your lesson..." required></textarea>
                                <p class="validator-hint hidden">Description is required.</p>
                            </fieldset>
                            
                            <div class="modal-action">
                                <button type="button" class="btn" onclick="closeNewLessonModal()">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Lesson</button>
                            </div>
                        </form>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>Close</button>
                    </form>
                </dialog>
            `;

            // Add modal to body if it doesn't exist
            if (!document.getElementById('new-lesson-modal')) {
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }

            // Show modal
            const modal = document.getElementById('new-lesson-modal');
            modal.showModal();

            // Handle form submission
            $('#new-lesson-form').on('submit', function(e) {
                e.preventDefault();
                const title = $('#lesson-title').val().trim();
                const description = $('#lesson-description').val().trim();

                if (!title || !description) {
                    return;
                }

                $.ajax({
                    url: '/admin/quizzes/lesson/store',
                    method: 'POST',
                    data: {
                        title: title,
                        description: description,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Add new lesson to select
                        $('#quiz-lesson').append(
                            `<option value="${response.id}" selected>${response.title}</option>`
                        );
                        closeNewLessonModal();
                        alert('Lesson created successfully!');
                    },
                    error: function(xhr) {
                        alert('Failed to create lesson. Please try again.');
                        console.error(xhr.responseText);
                    }
                });
            });
        }

        function closeNewLessonModal() {
            const modal = document.getElementById('new-lesson-modal');

            $('#new-lesson-form').trigger('reset');
            modal.close();
            $('#new-lesson-form').off('submit');
        }

        function saveQuiz() {
            const title = $('#quiz-title').val().trim();
            const description = $('#quiz-description').val().trim();
            const choicesType = $('#quiz-choices-type').val();
            const lessonId = $('#quiz-lesson').val();

            if (!validateStep(currentStep)) {
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            formData.append('choices_type', choicesType);
            formData.append('lesson_id', lessonId);

            // Notes
            notes.forEach((noteId, index) => {
                const noteCard = $(`#${noteId}`);
                const noteText = noteCard.find('input[type="text"]').first().val().trim();
                const correctAnswer = noteCard.find('input[type="radio"]:checked').val();
                const mediaInput = noteCard.find('input[type="file"]')[0];
                const mediaFile = mediaInput?.files[0] ?? null;

                formData.append(`notes[${index}][note_text]`, noteText);
                formData.append(`notes[${index}][correct_choice]`, correctAnswer);

                if (mediaFile) {
                    formData.append(`notes[${index}][media]`, mediaFile);
                }
            });

            // Questions
            questions.forEach((questionId, index) => {
                const questionCard = $(`#${questionId}`);
                const questionText = questionCard.find(`#question-text-${questionId}`).val().trim();
                const correctAnswer = questionCard.find(`input[name="correct-${questionId}"]:checked`).val();

                formData.append(`questions[${index}][question_text]`, questionText);
                formData.append(`questions[${index}][correct_choice]`, correctAnswer);

                // Choices
                questionCard.find('.choice-item').each(function (cIndex) {
                    const $choiceItem = $(this);

                    if (choicesType === 'media') {
                        const fileInput = $choiceItem.find('input[type="file"]')[0];
                        const file = fileInput?.files[0] ?? null;

                        // always null text
                        formData.append(`questions[${index}][choices][${cIndex}][choice_text]`, '');
                        if (file) {
                            formData.append(`questions[${index}][choices][${cIndex}][choice_media]`, file);
                        }
                    } else {
                        const textInput = $choiceItem.find('input[type="text"]');
                        const text = textInput.val().trim();

                        formData.append(`questions[${index}][choices][${cIndex}][choice_text]`, text);
                        // always null media
                        formData.append(`questions[${index}][choices][${cIndex}][choice_media]`, '');
                    }
                });

                // Optional question-level media
                const qMediaInput = questionCard.find(`#question-media-${questionId}`)[0];
                const qMediaFile = qMediaInput?.files[0] ?? null;
                if (qMediaFile) {
                    formData.append(`questions[${index}][media]`, qMediaFile);
                }
            });

            $.ajax({
                url: '/admin/quizzes/store',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    window.location.href = response.redirect;
                },
                error: function (xhr) {
                    alert('Failed to save quiz. Please check your inputs.');
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
    @endpush
    @push('scripts')
    <script>
        // Initialize theme from the app layout's theme
        let currentTheme = document.documentElement.getAttribute('data-theme');
    </script>
    @endpush
</x-app-layout>