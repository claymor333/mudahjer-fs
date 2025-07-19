<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Edit Quiz') }}
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
                            <li class="step" id="step-2">Questions</li>
                        </ul>
                    </div>

                    <!-- Right side: Back button when on step 2 -->
                    <div class="text-right">
                        <button type="button" class="btn btn-outline btn-sm hidden" id="step-back-btn" onclick="prevStep()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <form id="quizForm" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="quiz-id" value="{{ $quiz->id }}">
                
                <!-- Step 1: Quiz Details -->
                <div id="quiz-details" class="card bg-base-200 shadow-lg">
                    <div class="card-body">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">
                                <span class="label-text font-medium text-lg">Quiz Title</span>
                            </legend>
                            <input type="text" id="quiz-title" class="w-full input input-bordered validator" 
                                placeholder="Enter quiz title..." required value="{{ $quiz->title }}" />
                            <p class="validator-hint hidden">Title is required.</p>

                            <legend class="fieldset-legend">
                                <span class="label-text font-medium text-lg">Description</span>
                            </legend>
                            <textarea id="quiz-description" class="w-full textarea textarea-bordered validator" 
                                rows="4" placeholder="Describe your quiz..." required>{{ $quiz->description }}</textarea>
                            <p class="validator-hint hidden">Description is required.</p>

                            <legend class="fieldset-legend">
                                <span class="label-text font-medium text-lg">Choices Type</span>
                            </legend>
                            <select id="quiz-choices-type" class="w-full select select-bordered">
                                <option value="text" {{ $quiz->choices_type == 'text' ? 'selected' : '' }}>Question and Text Choices</option>
                                <option value="media" {{ $quiz->choices_type == 'media' ? 'selected' : '' }}>Question and Media Choices</option>
                            </select>

                            <legend class="fieldset-legend">
                                <span class="label-text font-medium text-lg">Associated Lesson</span>
                            </legend>
                            <div class="join w-full">
                                <select id="quiz-lesson" class="select select-bordered join-item w-full" required>
                                    <option value="" disabled selected>Select a lesson</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" {{ $lesson->id == $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn join-item" onclick="createNewLesson()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="validator-hint hidden">Please select a lesson</p>
                        </fieldset>

                        <div class="card-actions justify-end mt-8">
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Next Step
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Questions -->
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
                        <div class="questions-carousel" id="questions-carousel"></div>
                        
                        <!-- Navigation Buttons - Fixed on sides -->
                        <div class="fixed left-4 top-1/2 flex flex-col items-center gap-2">
                            <button type="button" class="question-nav prev btn btn-circle btn-ghost hidden" onclick="prevQuestion()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span class="flex-row" id="prevKbd">
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">Q</kbd>
                            </span>
                        </div>
                        
                        <div class="fixed right-4 top-1/2 flex flex-col items-center gap-2">
                            <button type="button" class="question-nav next btn btn-circle btn-ghost hidden" onclick="nextQuestion()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <span class="flex-row" id="nextKbd">
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">E</kbd>
                            </span>
                        </div>
                    </div>

                    @include('admin.quizzes.partials.wizard-fab')
                </div>
            </form>
        </div>
    </div>

    <!-- Media Preview Modal -->
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
        let questionCount = 0;
        let currentQuestionIndex = 0;
        let questions = [];
        let deletedQuestions = [];
        let currentTheme = document.documentElement.getAttribute('data-theme');
        let keyboardNavigationEnabled = true;

        // Initialize the form with the existing quiz data
        $(document).ready(function() {
            // Pre-fill the quiz details
            $('#quiz-title').val('{{ $quiz->title }}');
            $('#quiz-description').val('{{ $quiz->description }}');

            // Initialize existing questions
            $('.question-card').each(function() {
                const id = $(this).attr('id');
                questions.push(id);
                
                // Set media data attribute if question has media
                if ($(this).find('.media-preview').length > 0) {
                    $(this).attr('data-has-media', 'true');
                }
            });

            updateQuestionCounter();
            updateNavigationButtons();
            navigateToQuestion(0);

            @foreach($quiz->questions as $question)
                addQuestion(
                    '{{ $question->id }}',
                    '{{ addslashes($question->question_text) }}',
                    '{{ $question->media_path }}',
                    @json($question->choices->toArray())
                );
            @endforeach

            if (questions.length > 0) {
                navigateToQuestion(0);
                updateQuestionCounter();
                updateNavigationButtons();
            }
        });

        function nextStep() {
            if (currentStep === 1) {
                // Validate form
                const title = $('#quiz-title').val().trim();
                const description = $('#quiz-description').val().trim();

				if (!title || !description) {
					const emptyInputs = $('#quiz-details input, #quiz-details textarea').filter(function () {
						return !$(this).val().trim();
					});

					emptyInputs.addClass('input-error');
					setTimeout(() => emptyInputs.removeClass('input-error'), 2000);
					return;
				}

				// Update DaisyUI stepper
				$('#step-2').addClass('step-primary');

				// Show questions step
				$('#quiz-details').addClass('hidden');
				$('#questions-step').removeClass('hidden');

				// Add first question if none exist
				if (questions.length === 0) {
					addQuestion();
				}

				// Update step title and show back button
				$('#step-title').text('Quiz Questions');
				$('#step-back-btn').removeClass('hidden');

				currentStep = 2;
			}
		}

        function prevStep() {
			if (currentStep === 2) {
				// Move back to step 1
				$('#step-2').removeClass('step-primary');
				$('#step-1').addClass('step-primary');

				// Show quiz details, hide questions
				$('#questions-step').addClass('hidden');
				$('#quiz-details').removeClass('hidden');

				// Update title & hide back button
				$('#step-title').text('Quiz Information');
				$('#step-back-btn').addClass('hidden');

				currentStep = 1;
			}
		}

        function addQuestion(existingId = null, questionText = '', mediaPath = null, choices = []) {
            const nextNumber = questions.length + 1;
            const questionId = existingId || `question-${Date.now()}`;
            
            const questionHtml = `
                <div class="question-card card bg-base-200 shadow-lg" id="${questionId}" data-sequence="${nextNumber}" ${existingId ? `data-existing-id="${existingId}"` : ''}>
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Question ${nextNumber}</h3>
                            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeQuestion('${questionId}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <input type="hidden" name="questions[${nextNumber-1}][id]" value="${existingId || ''}">
                        
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Question Text</span>
                            </legend>
                            <input type="text" name="questions[${nextNumber-1}][question_text]" class="w-full input input-bordered validator" 
                                placeholder="Enter your question..." required value="${questionText}">
                            <p class="validator-hint hidden">Question text is required.</p>

                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Media (Optional)</span>
                            </legend>
                            <input type="file" name="questions[${nextNumber-1}][media]" class="w-full file-input file-input-bordered" 
                                accept="image/*,video/*" onchange="previewMedia(this, '${questionId}')">
                            <div class="media-preview mt-4" id="media-preview-${questionId}">
                                ${mediaPath ? `
                                    <div class="media-preview relative">
                                        ${mediaPath.match(/\.(jpg|jpeg|png|gif|webp)$/i) 
                                            ? `<img src="/storage/${mediaPath}" class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">` 
                                            : `<video controls class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
                                                <source src="/storage/${mediaPath}" type="video/mp4">
                                            </video>`
                                        }
                                        <button type="button" class="btn btn-sm btn-circle btn-error absolute top-2 right-2" onclick="removeMedia('${questionId}')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <input type="hidden" name="questions[${nextNumber-1}][existing_media]" value="${mediaPath}">
                                    </div>
                                ` : ''}
                            </div>

                            <legend class="legend">
                                <span class="font-medium text-lg">Answer Choices</span>
                            </legend>
                            <div class="choices-container space-y-3" id="choices-${questionId}">
                                ${choices.length > 0 ? choices.map((choice, index) => `
                                    <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg" ${choice.id ? `data-choice-id="${choice.id}"` : ''}>
                                        <input type="hidden" name="questions[${nextNumber-1}][choices][${index}][id]" value="${choice.id || ''}">
                                        <input type="radio" name="questions[${nextNumber-1}][correct_choice]" class="radio radio-primary" 
                                            value="${index}" ${choice.is_correct ? 'checked' : ''}>
                                        <input type="text" name="questions[${nextNumber-1}][choices][${index}][choice_text]" 
                                            class="input input-bordered flex-1 validator" placeholder="Choice ${index + 1}" 
                                            required value="${choice.choice_text}">
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost ${choices.length <= 2 ? 'opacity-50' : ''}" 
                                            onclick="removeChoice(this)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                `).join('') : `
                                    <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                                        <input type="radio" name="questions[${nextNumber-1}][correct_choice]" class="radio radio-primary" value="0">
                                        <input type="text" name="questions[${nextNumber-1}][choices][0][choice_text]" 
                                            class="input input-bordered flex-1 validator" placeholder="Choice 1" required>
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50" onclick="removeChoice(this)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                                        <input type="radio" name="questions[${nextNumber-1}][correct_choice]" class="radio radio-primary" value="1">
                                        <input type="text" name="questions[${nextNumber-1}][choices][1][choice_text]" 
                                            class="input input-bordered flex-1 validator" placeholder="Choice 2" required>
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50" onclick="removeChoice(this)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                `}
                            </div>
                            <button type="button" class="btn btn-sm btn-outline btn-primary mt-3" onclick="addChoice('${questionId}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Choice
                            </button>
                        </fieldset>
                    </div>
                </div>
            `;

            $('#questions-carousel').append(questionHtml);
            questions.push(questionId);
            
            // If this is a new question (not loaded from existing data), navigate to it
            if (!existingId) {
                navigateToQuestion(questions.length - 1);
                updateQuestionCounter();
                updateNavigationButtons();
            }
            
            return questionId;
        }

        function createChoiceHtml(questionId, index, choice = null) {
            return `
                <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg" ${choice?.id ? `data-choice-id="${choice.id}"` : ''}>
                    <input type="radio" name="correct-${questionId}" class="radio radio-primary" 
                        value="${index}" ${choice?.isCorrect ? 'checked' : ''}>
                    <input type="text" class="input input-bordered flex-1 validator" 
                        placeholder="Choice ${index + 1}" required value="${choice?.text || ''}">
                    <button type="button" class="btn btn-sm btn-circle btn-ghost ${index < 2 ? 'opacity-50' : ''}" onclick="removeChoice(this)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
        }

        function createMediaPreview(mediaPath) {
            const isVideo = /\.(mp4|webm)$/i.test(mediaPath);
            if (isVideo) {
                return `
                    <video controls class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
                        <source src="/storage/${mediaPath}" type="video/${mediaPath.split('.').pop()}">
                    </video>
                `;
            } else {
                return `
                    <img src="/storage/${mediaPath}" class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
                `;
            }
        }

        function removeQuestion(questionId) {
            const questionIndex = questions.indexOf(questionId);
            if (questionIndex === -1) return;
            
            if (questions.length <= 1) {
                alert('You must have at least one question!');
                return;
            }

            const questionCard = $(`#${questionId}`);
            const existingId = questionCard.data('existing-id');
            if (existingId) {
                deletedQuestions.push(existingId);
            }

            // Animate the removal
            questionCard.fadeOut(300, function() {
                $(this).remove();
                questions.splice(questionIndex, 1);
                
                updateQuestionSequences();
                
                if (currentQuestionIndex >= questions.length) {
                    currentQuestionIndex = questions.length - 1;
                }
                
                navigateToQuestion(currentQuestionIndex);
                updateQuestionCounter();
                updateNavigationButtons();
            });
        }

		
        function addChoice(questionId) {
            const questionCard = $(`#${questionId}`);
            const questionIndex = parseInt(questionCard.data('sequence')) - 1;
            const choicesContainer = $(`#choices-${questionId}`);
            const choiceCount = choicesContainer.children().length;
            
            const choiceHtml = `
                <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                    <input type="hidden" name="questions[${questionIndex}][choices][${choiceCount}][id]" value="">
                    <input type="radio" name="questions[${questionIndex}][correct_choice]" class="radio radio-primary" value="${choiceCount}">
                    <input type="text" name="questions[${questionIndex}][choices][${choiceCount}][choice_text]" 
                        class="input input-bordered flex-1 validator" placeholder="Choice ${choiceCount + 1}" required>
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
                if (currentStep === 2 && currentQuestionIndex === 0) {
                    prevStep();
                } else if (currentStep === 2) {
                    prevQuestion();
                }
            }
            if (e.ctrlKey && (e.key === 'e' || e.key === 'E')) {
                e.preventDefault();
                if (currentStep === 1) {
                    nextStep();
                } else if (currentStep === 2) {
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

		function updateNavigationButtons() {
			const prevBtn = $('.question-nav.prev');
			const nextBtn = $('.question-nav.next');
			const prevKbd = $('#prevKbd');
			const nextKbd = $('#nextKbd');

			if (questions.length <= 1) {
				prevBtn.addClass('hidden');
				nextBtn.addClass('hidden');

				prevKbd.addClass('hidden');
				nextKbd.addClass('hidden');
			} else {
				prevBtn.removeClass('hidden');
				nextBtn.removeClass('hidden');

				prevKbd.removeClass('hidden');
				nextKbd.removeClass('hidden');

				// disable prev if at first question
				prevBtn.prop('disabled', currentQuestionIndex === 0);

				// next is never disabled, because pressing it creates a new question if needed
				nextBtn.prop('disabled', false);
			}
		}

		function previewMedia(input, questionId) {
            const preview = $(`#media-preview-${questionId}`);
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
                    <button type="button" class="btn btn-sm btn-circle btn-error absolute top-2 right-2" onclick="removeMedia('${questionId}')">
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
			const questionCard = $(`#${questionId}`);
			const preview = $(`#media-preview-${questionId}`);
			const fileInput = questionCard.find('input[type="file"]');

			preview.empty();
			fileInput.val('');
			questionCard.removeAttr('data-has-media');
			questionCard.attr('data-remove-media', 'true'); // mark it for saveQuiz
		}

        function closeMediaPreview() {
            const modal = $('#mediaPreviewModal');
            modal.removeClass('active');
            modal.find('img, video').addClass('hidden');
        }

        function updateQuestionSequences() {
            questions.forEach((qId, index) => {
                const questionCard = $(`#${qId}`);
                const newSequence = index + 1;
                questionCard
                    .attr('data-sequence', newSequence)
                    .find('h3')
                    .text(`Question ${newSequence}`);
            });
        }

		function openMediaPreview(src, isVideo = false) {
            const modal = $('#mediaPreviewModal');
            const img = modal.find('img');
            const video = modal.find('video');
            const source = modal.find('source');

            if (isVideo) {
                img.addClass('hidden');
                video.removeClass('hidden');
                source.attr('src', src);
                video[0].load();
            } else {
                video.addClass('hidden');
                img.removeClass('hidden').attr('src', src);
            }

            modal.addClass('active');
        }

		function cancelQuiz() {
			if (confirm('Are you sure you want to cancel? All progress will be lost.')) {
				window.location.href = '/admin';
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
            const quizId = $('#quiz-id').val();
            const title = $('#quiz-title').val().trim();
            const description = $('#quiz-description').val().trim();
            const choicesType = $('#quiz-choices-type').val();
            const lessonId = $('#quiz-lesson').val();

            if (!title || !description) {
                alert('Please fill in all quiz details!');
                return;
            }

            if (questions.length === 0) {
                alert('Please add at least one question!');
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('title', title);
            formData.append('description', description);
            formData.append('choices_type', choicesType);
            formData.append('lesson_id', lessonId);
            formData.append('deleted_questions', JSON.stringify(deletedQuestions));

            let isValid = true;

            questions.forEach((questionId, index) => {
                const questionCard = $(`#${questionId}`);
                const existingId = questionCard.data('existing-id');
                const questionText = questionCard.find('input[type="text"]').first().val().trim();
                const correctAnswer = questionCard.find('input[type="radio"]:checked').val();
                const choices = [];
                let choiceCounter = 0;

                questionCard.find('.choice-item').each(function() {
                    const $choice = $(this);
                    const choiceText = $choice.find('input[type="text"]').val().trim();
                    const choiceId = $choice.data('choice-id');
                    
                    formData.append(`questions[${index}][choices][${choiceCounter}][choice_text]`, choiceText);
                    if (choiceId) {
                        formData.append(`questions[${index}][choices][${choiceCounter}][id]`, choiceId);
                    }
                    choiceCounter++;
                });

                const mediaInput = questionCard.find('input[type="file"]')[0];
                const mediaFile = mediaInput?.files[0];
                const removeMedia = questionCard.find('.media-preview').length === 0 && questionCard.attr('data-has-media');

				if (!questionText || correctAnswer === undefined || choiceCounter < 2) {
					isValid = false;
				} else {
					if (existingId) {
						formData.append(`questions[${index}][id]`, existingId);
					}
					formData.append(`questions[${index}][question_text]`, questionText);
					formData.append(`questions[${index}][correct_choice]`, correctAnswer);

					// Handle media file
					if (mediaFile) {
						formData.append(`questions[${index}][media]`, mediaFile);
					}

					// Handle media removal
					if (questionCard.attr('data-remove-media') === 'true') {
						formData.append(`questions[${index}][remove_media]`, '1');
					}
				}

                if (!questionText || correctAnswer === undefined || choiceCounter < 2) {
                    isValid = false;
                } else {
                    if (existingId) {
                        formData.append(`questions[${index}][id]`, existingId);
                    }
                    formData.append(`questions[${index}][question_text]`, questionText);
                    formData.append(`questions[${index}][correct_choice]`, correctAnswer);
                    
                    // Handle media file
                    if (mediaFile) {
                        formData.append(`questions[${index}][media]`, mediaFile);
                    }
                    // Handle media removal
                    if (removeMedia) {
                        formData.append(`questions[${index}][remove_media]`, '1');
                    }
                }
            });

			console.log('Form Data:', formData);

            if (!isValid) {
                alert('Please complete all questions with at least 2 choices and select correct answers!');
                return;
            }

            $.ajax({
                url: `/admin/quizzes/${quizId}/update`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                success: function(response) {
                    window.location.href = response.redirect;
                },
                error: function(xhr) {
                    let message = 'Failed to update quiz. Please check your inputs.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        const firstError = Object.values(errors)[0][0];
                        message = firstError || message;
                    }
                    alert(message);
                    console.error(xhr.responseText);
                }
            });
        }

        // Rest of the JavaScript functions remain the same as in create-quiz.blade.php
        // Including: nextStep, prevStep, addChoice, removeChoice, updateChoiceIndexes,
        // updateChoiceRemoveButtons, navigateToQuestion, prevQuestion, nextQuestion,
        // updateQuestionCounter, updateNavigationButtons, showPreviewModal, etc.
    </script>
    @endpush

	@push('scripts')
		<script>
			let currentTheme = document.documentElement.getAttribute('data-theme');
		</script>
	@endpush
</x-app-layout>
