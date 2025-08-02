<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Create Quiz') }}
        </h2>
    </x-slot>

    @vite(['resources/css/quiz-wizard.css'])

    @vite(
        [
            'resources/css/quiz-wizard.css', 
            'resources/js/quizzes/create/media-handling-create.js', 
            'resources/js/quizzes/create/quiz-questions-create.js', 
            'resources/js/quizzes/create/quiz-notes-create.js', 
            'resources/js/quizzes/create/quiz-create-validator.js'
        ]
    )

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
            <div id="quiz-details" class="card card-border border-base-300 dark:bg-base-200 shadow-lg">
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
                                    <option value="{{ $lesson->id }}">Level {{ $lesson->required_level }} - {{ $lesson->title }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary join-item" onclick="openLessonModal('create')" title="Create New Lesson">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-info join-item" onclick="openLessonModal('edit')" title="Edit Lesson" id="editLessonBtn" disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            {{-- Delete button fully functional but cascading actions will delete all quizzes under a particular lesson --}}
                            {{-- <button type="button" class="btn btn-error join-item" onclick="deleteLesson()" title="Delete Lesson" id="deleteLessonBtn" disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button> --}}
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
                                <kbd class="kbd !bg-transparent kbd-sm">CTRL</kbd> + <kbd class="kbd !bg-transparent kbd-sm">Q</kbd>
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
                                <kbd class="kbd !bg-transparent kbd-sm">CTRL</kbd> + <kbd class="kbd !bg-transparent kbd-sm">E</kbd>
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
                                <kbd class="kbd !bg-transparent kbd-sm">CTRL</kbd> + <kbd class="kbd !bg-transparent kbd-sm">Q</kbd>
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
                                <kbd class="kbd !bg-transparent kbd-sm">CTRL</kbd> + <kbd class="kbd !bg-transparent kbd-sm">E</kbd>
                            </span>
                        </div>
                    </div>
                </div>

                @include('admin.quizzes.partials.wizard-fab')
            </div>
        </form>
    </div>

    <!-- Media Preview Modal -->
    {{-- <div class="preview-modal" id="mediaPreviewModal">
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
    </div> --}}

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

    <!-- Lesson Modal -->
    <dialog id="lesson_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4" id="lessonModalTitle">Create New Lesson</h3>
            <form id="lessonForm" class="space-y-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text">Title</span>
                    </legend>
                    <input type="text" id="lessonTitle" class="input input-bordered w-full" required />
                    <legend class="fieldset-legend">
                        <span class="label-text">Required Level</span>
                    </legend>
                    <input type="number" id="lessonLevel" class="input input-bordered w-full" min="0" value="0" required />
                    <legend class="fieldset-legend">
                        <span class="label-text">Description</span>
                    </legend>
                    <textarea id="lessonDescription" class="textarea textarea-bordered w-full" rows="3"></textarea>
                </fieldset>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="closeLessonModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="lessonSubmitBtn">Create</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    <script>
        /// passed to lesson management script
        const allLessons = @json($lessons->keyBy('id'));
    </script>

    @push('scripts')
    <script>
        let currentStep = 1;
        let currentQuestionIndex = 0;
        let currentNoteIndex = 0;
        let questions = [];
        let notes = [];
        let currentTheme = 'dark';
        let keyboardNavigationEnabled = true;
        let currentLessonAction = 'create';

        /// jquery Lesson management
        $(document).ready(function () {
            const $lessonSelect = $('#quiz-lesson');
            const $editBtn = $('#editLessonBtn');
            const $deleteBtn = $('#deleteLessonBtn');
            const $modal = $('#lesson_modal');
            const $form = $('#lessonForm');
            const $titleInput = $('#lessonTitle');
            const $descriptionInput = $('#lessonDescription');
            const $levelInput = $('#lessonLevel');
            const $modalTitle = $('#lessonModalTitle');
            const $submitBtn = $('#lessonSubmitBtn');

            updateLessonActionButtons();

            $lessonSelect.on('change', updateLessonActionButtons);

            function updateLessonActionButtons() {
                const hasValue = $lessonSelect.val() !== '';
                $editBtn.prop('disabled', !hasValue);
                $deleteBtn.prop('disabled', !hasValue);
            }

            window.openLessonModal = function (mode) {
                currentLessonAction = mode;
                $form.trigger('reset');

                if (mode === 'edit') {
                    const selectedId = $lessonSelect.val();
                    if (!selectedId || !allLessons[selectedId]) return;

                    const lesson = allLessons[selectedId];

                    $modalTitle.text('Edit Lesson');
                    $submitBtn.text('Update');
                    $titleInput.val(lesson.title);
                    $descriptionInput.val(lesson.description);
                    $levelInput.val(lesson.required_level);
                } else {
                    $modalTitle.text('Create New Lesson');
                    $submitBtn.text('Create');
                }

                $modal[0].showModal();
            };

            window.closeLessonModal = function () {
                $modal[0].close();
            };

            $form.on('submit', function (e) {
                e.preventDefault();

                const lessonId = currentLessonAction === 'edit' ? $lessonSelect.val() : null;

                const data = {
                    title: $titleInput.val(),
                    description: $descriptionInput.val(),
                    required_level: $levelInput.val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                const url = currentLessonAction === 'edit'
                    ? `/admin/quizzes/lessons/${lessonId}/update`
                    : '/admin/quizzes/lessons/store';

                const method = currentLessonAction === 'edit' ? 'PUT' : 'POST';

                $.ajax({
                    url,
                    method,
                    contentType: 'application/json',
                    data: JSON.stringify(data)
                })
                .done(function (lesson) {
                    allLessons[lesson.id] = lesson;

                    if (currentLessonAction === 'edit') {
                        const $option = $lessonSelect.find(`option[value="${lesson.id}"]`);
                        $option.text(`Level ${lesson.required_level} - ${lesson.title}`);
                    } else {
                        const newOption = new Option(`Level ${lesson.required_level} - ${lesson.title}`, lesson.id, true, true);
                        $lessonSelect.append(newOption);
                    }

                    updateLessonActionButtons();
                    closeLessonModal();
                    showToast('success', `Lesson ${currentLessonAction === 'edit' ? 'updated' : 'created'} successfully`);
                })
                .fail(function (xhr) {
                    console.error(xhr.responseText);
                    showToast('error', `Failed to ${currentLessonAction} lesson`);
                });
            });

            window.deleteLesson = function () {
                const lessonId = $lessonSelect.val();
                const lessonTitle = $lessonSelect.find('option:selected').text();

                if (!lessonId || !confirm(`Are you sure you want to delete "${lessonTitle}"? This cannot be undone.`)) {
                    return;
                }

                $.ajax({
                    url: `/admin/quizzes/lessons/${lessonId}/delete`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .done(function () {
                    delete allLessons[lessonId];
                    $lessonSelect.find(`option[value="${lessonId}"]`).remove();
                    $lessonSelect.val('');
                    updateLessonActionButtons();
                    showToast('success', 'Lesson deleted successfully');
                })
                .fail(function () {
                    showToast('error', 'Failed to delete lesson');
                });
            };

            function showToast(type, message) {
                alert(message); // Replace this with your own toast logic
            }
        });

        function toggleTheme() {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
        }

        function nextStep() {
            // Step 1 validation (Quiz Details)
            if (currentStep === 1) {

                const { isValid, firstInvalidElement, firstErrorIndex, errorType } = validateStep(currentStep);

                if (!isValid) {
                    // Navigate carousel to error item
                    if (errorType === 'note') {
                        navigateToNote(firstErrorIndex);
                    } else if (errorType === 'question') {
                        navigateToQuestion(firstErrorIndex);
                    }

                    // Scroll to the invalid field after DOM updates
                    setTimeout(() => {
                        if (firstInvalidElement) {
                            $('html, body').animate({
                                scrollTop: $(firstInvalidElement).offset().top - 100
                            }, 500);

                            $(firstInvalidElement).addClass('animate-pulse');
                            setTimeout(() => $(firstInvalidElement).removeClass('animate-pulse'), 1000);
                        }
                    }, 300);

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

                const { isValid, firstInvalidElement, firstErrorIndex, errorType } = validateStep(currentStep);

                if (!isValid) {
                    // Navigate carousel to error item
                    if (errorType === 'note') {
                        navigateToNote(firstErrorIndex);
                    } else if (errorType === 'question') {
                        navigateToQuestion(firstErrorIndex);
                    }

                    // Scroll to the invalid field after DOM updates
                    setTimeout(() => {
                        if (firstInvalidElement) {
                            $('html, body').animate({
                                scrollTop: $(firstInvalidElement).offset().top - 100
                            }, 500);

                            $(firstInvalidElement).addClass('animate-pulse');
                            setTimeout(() => $(firstInvalidElement).removeClass('animate-pulse'), 1000);
                        }
                    }, 300);

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
                    prevQuestion();
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

            const { isValid, firstInvalidElement, firstErrorIndex, errorType } = validateStep(currentStep);

            console.log(choicesType)
            if (!isValid) {
                // Navigate carousel to error item
                if (errorType === 'note') {
                    console.log("invalid n")
                    navigateToNote(firstErrorIndex);
                } else if (errorType === 'question') {
                    console.log("invalid q")
                    navigateToQuestion(firstErrorIndex);
                } else {
                    console.log("valid")
                }

                // Scroll to the invalid field after DOM updates
                setTimeout(() => {
                    if (firstInvalidElement) {
                        $('html, body').animate({
                            scrollTop: $(firstInvalidElement).offset().top - 100
                        }, 500);

                        $(firstInvalidElement).addClass('animate-pulse');
                        setTimeout(() => $(firstInvalidElement).removeClass('animate-pulse'), 1000);
                    }
                }, 300);

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
                if (choicesType === 'media') {
                    const questionText = questionCard.find(`#question-text-${questionId}`).val().trim();
                    formData.append(`questions[${index}][question_text]`, questionText);
                } else {
                    const qMediaInput = questionCard.find(`#question-media-${questionId}`)[0];
                    const qMediaFile = qMediaInput?.files[0] ?? null;
                    if (qMediaFile) {
                        formData.append(`questions[${index}][media]`, qMediaFile);
                    }
                }

                const correctAnswer = questionCard.find(`input[name="correct-${questionId}"]:checked`).val();
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