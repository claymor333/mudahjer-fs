<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Create Quiz') }}
        </h2>
    </x-slot>

    <style>
        /* Modified Carousel Animation */
        .questions-carousel {
            position: relative;
            min-height: 70vh;
            height: auto;
            perspective: 2000px;
            transform-style: preserve-3d;
            margin-bottom: 100px; /* Space for FABs */
            overscroll-behavior: none;
            padding-bottom: 100px;
        }

        .question-card {
            position: absolute;
            width: 100%;
            height: fit-content;
            max-width: 48rem; /* 1024px */
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            transition: all 0.5s ease-in-out;
            opacity: 0;
            filter: blur(4px);
            pointer-events: none;
            padding-bottom: 2rem;
        }

        .question-card.prev-2 {
            opacity: 0.3;
            filter: blur(6px);
            transform: translateX(calc(-50% - 100%)) translateZ(-200px) rotateY(25deg);
        }

        .question-card.prev-1 {
            opacity: 0.5;
            filter: blur(3px);
            transform: translateX(calc(-50% - 50%)) translateZ(-100px) rotateY(15deg);
        }

        .question-card.active {
            opacity: 1;
            filter: blur(0);
            transform: translateX(-50%);
            z-index: 10;
            pointer-events: all;
        }

        .question-card.next-1 {
            opacity: 0.5;
            filter: blur(3px);
            transform: translateX(calc(-50% + 50%)) translateZ(-100px) rotateY(-15deg);
        }

        .question-card.next-2 {
            opacity: 0.3;
            filter: blur(6px);
            transform: translateX(calc(-50% + 100%)) translateZ(-200px) rotateY(-25deg);
        }

        /* Stepper Enhancement
        .stepper-line {
            position: relative;
            height: 4px;
            background: hsl(var(--bc) / 0.3);
            border-radius: 2px;
            overflow: hidden;
        }

        .stepper-progress {
            height: 100%;
            background: hsl(var(--p) / 0.9);
            width: 0%;
            transition: width 0.5s ease-in-out;
            border-radius: 2px;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
            border: 2px solid transparent;
        }

        .step-circle.active {
            color: hsl(var(--pc));
            border-color: hsl(var(--p));
            box-shadow: 0 0 0 4px hsl(var(--p) / 0.3);
        }

        .step-circle.completed {
            border-color: hsl(var(--su));
        }

        .step-circle.inactive {
            background: transparent;
            color: hsl(var(--bc) / 0.8);
            border-color: hsl(var(--bc) / 0.5);
        } */

        /* Question Navigation
        .question-nav {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            z-index: 20;
            transition: all 0.3s ease;
        }

        .question-nav.prev {
            left: 30px;
        }

        .question-nav.next {
            right: 30px;
        } */

        .question-nav:hover {
            transform: translateY(-10%) scale(1.1);
        }

        /* Choice Animation */
        .choice-item {
            transition: all 0.3s ease;
        }

        .choice-item:hover {
            transform: translateX(5px);
        }

        /* Media Preview */
        .media-preview {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Preview Modal */
        .preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .preview-modal.active {
            opacity: 1;
            pointer-events: all;
        }

        .preview-modal .content {
            max-width: 70vw;
            max-height: 70vh;
            position: relative;
        }

        .preview-modal img,
        .preview-modal video {
            max-width: 70vw;
            max-height: 70vh;
            object-fit: contain;
        }

        .preview-modal .close-btn {
            position: absolute;
            top: -3rem;
            right: 0;
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Floating Action Buttons */
        .fab-container {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1rem;
            z-index: 50;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .question-nav {
                position: static;
                transform: none;
                margin: 1rem 0;
            }
            
            .question-nav.prev {
                left: auto;
            }
            
            .question-nav.next {
                right: auto;
            }
        }

        /* Dark mode compatibility */
        @media (prefers-color-scheme: dark) {
            .question-nav {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
            }
        }
    </style>

    <div class="py-12">
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
            <!-- Step 1: Quiz Details -->
            <div id="quiz-details" class="card bg-base-200 shadow-lg">
                <div class="card-body">
                    
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Quiz Title</span>
                        </legend>
                        <input type="text" id="quiz-title" class="w-full input input-bordered validator" placeholder="Enter quiz title..." required />
                        <p class="validator-hint">Title is required.</p>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Description</span>
                        </legend>
                        <textarea id="quiz-description" class="w-full textarea textarea-bordered validator" rows="4" placeholder="Describe your quiz..." required></textarea>
                        <p class="validator-hint">Description is required.</p>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">
                            <span class="label-text font-medium text-lg">Category</span>
                        </legend>
                        <select id="quiz-category" class="w-full select select-bordered">
                            <option value="">Select a category</option>
                            <option value="general">General Knowledge</option>
                            <option value="science">Science</option>
                            <option value="history">History</option>
                            <option value="sports">Sports</option>
                            <option value="entertainment">Entertainment</option>
                            <option value="technology">Technology</option>
                        </select>
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
                    <div class="questions-carousel" id="questions-carousel">
                    </div>
                    
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

                <!-- Floating Action Buttons -->
                <div class="fab-container" id="fab-container">
					<div class="tooltip tooltip-left" data-tip="Cancel Quiz">
						<button type="button" class="btn btn-circle btn-lg btn-error fab" onclick="cancelQuiz()">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
							</svg>
						</button>
					</div>
					<div class="tooltip tooltip-left">
                        <div class="tooltip-content">
                            <div class="flex-col">
                                <p class="mb-2">
                                    Save Quiz
                                </p>
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">S</kbd>
                            </div>
                        </div>
						<button type="button" class="btn btn-circle btn-lg btn-success fab" onclick="saveQuiz()">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
							</svg>
						</button>
					</div>
					<div class="tooltip tooltip-left">
                        <div class="tooltip-content">
                            <div class="flex-col">
                                <p class="mb-2">
                                    Add Question
                                </p>
                                <kbd class="kbd kbd-sm">CTRL</kbd> + <kbd class="kbd kbd-sm">E</kbd>
                            </div>
                        </div>
						<button type="button" class="btn btn-circle btn-lg btn-primary fab" onclick="addQuestion()">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
							</svg>
						</button>
					</div>
                </div>
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
        let questionCount = 0;
        let currentQuestionIndex = 0;
        let questions = [];
        let currentTheme = 'dark';

        function toggleTheme() {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
        }

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

        function addQuestion() {
            const nextNumber = questions.length + 1;
            const questionId = `question-${Date.now()}`; // Use timestamp instead of counter
            
            const questionHtml = `
                <div class="question-card card bg-base-200 shadow-lg" id="${questionId}" data-sequence="${nextNumber}">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Question ${nextNumber}</h3>
                            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeQuestion('${questionId}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Question Text</span>
                            </legend>
                            <input type="text" class="w-full input input-bordered validator" placeholder="Enter your question..." required>
                            <p class="validator-hint">Question text is required.</p>
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Media (Optional)</span>
                            </legend>
                            <input type="file" class="w-full file-input file-input-bordered" accept="image/*,video/*" onchange="previewMedia(this, '${questionId}')">
                            <div class="media-preview mt-4" id="media-preview-${questionId}"></div>
                        </fieldset>

                        <div class="form-control">
                            <legend class="legend">
                                <span class="font-medium text-lg">Answer Choices</span>
                            </legend>
                            <div class="choices-container space-y-3" id="choices-${questionId}">
                                <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                                    <input type="radio" name="correct-${questionId}" class="radio radio-primary" value="0">
                                    <input type="text" class="input input-bordered flex-1 validator" placeholder="Choice 1" required>
                                    <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50" onclick="removeChoice(this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                                    <input type="radio" name="correct-${questionId}" class="radio radio-primary" value="1">
                                    <input type="text" class="input input-bordered flex-1 validator" placeholder="Choice 2" required>
                                    <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50" onclick="removeChoice(this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline btn-primary mt-3" onclick="addChoice('${questionId}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Choice
                            </button>
                        </div>
                    </div>
                </div>
            `;

            $('#questions-carousel').append(questionHtml);
            questions.push(questionId);
            
            navigateToQuestion(questions.length - 1);
            updateQuestionCounter();
            updateNavigationButtons();
        }

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


		document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && (e.key === 'q' || e.key === 'Q')) {
                e.preventDefault(); // optional: prevent browser default behavior
                prevQuestion();
            }
            if (e.ctrlKey && (e.key === 'e' || e.key === 'E')) {
                e.preventDefault();
                nextQuestion();
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
            const preview = $(`#media-preview-${questionId}`);
            const fileInput = $(`#${questionId}`).find('input[type="file"]');
            
            preview.empty();
            fileInput.val('');
        }

        function closeMediaPreview() {
            const modal = $('#mediaPreviewModal');
            modal.removeClass('active');
            modal.find('img, video').addClass('hidden');
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
                window.location.href = '/admin/quizzes';
            }
        }

        function saveQuiz() {
            const title = $('#quiz-title').val().trim();
            const description = $('#quiz-description').val().trim();
            const category = $('#quiz-category').val();

            if (!title || !description) {
                alert('Please fill in all quiz details!');
                return;
            }

            if (questions.length === 0) {
                alert('Please add at least one question!');
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);

            let isValid = true;

            const questionArray = [];

            questions.forEach((questionId, index) => {
                const questionCard = $(`#${questionId}`);
                const questionText = questionCard.find('input[type="text"]').first().val().trim();
                const correctAnswer = questionCard.find('input[type="radio"]:checked').val();
                const choices = [];

                questionCard.find('.choice-item input[type="text"]').each(function () {
                    const choiceText = $(this).val().trim();
                    if (choiceText) {
                        choices.push(choiceText);
                    }
                });

                const mediaInput = questionCard.find('input[type="file"]')[0];
                const mediaFile = mediaInput?.files[0] ?? null;

                if (!questionText || !correctAnswer || choices.length < 2) {
                    isValid = false;
                } else {
                    formData.append(`questions[${index}][question_text]`, questionText);
                    formData.append(`questions[${index}][correct_choice]`, correctAnswer);
                    choices.forEach((choice, cIndex) => {
                        formData.append(`questions[${index}][choices][${cIndex}]`, choice);
                    });
                    if (mediaFile) {
                        formData.append(`questions[${index}][media]`, mediaFile);
                    }
                }
            });

            if (!isValid) {
                alert('Please complete all questions with at least 2 choices and select correct answers!');
                return;
            }

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