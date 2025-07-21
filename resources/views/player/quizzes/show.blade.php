<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row gap-12">
            <!-- Left: title & description -->
            <div class="shrink-0 max-w-full sm:max-w-[50vw] space-y-1">
                <!-- Title -->
                <h2 class="font-semibold text-xl text-base-content mb-1 truncate">
                    {{ $quiz->title }}
                </h2>

                <!-- Description -->
                <p class="text-base-content/70 mb-4 truncate">
                    {{ $quiz->description }}
                </p>
            </div>

            <!-- Right: progress -->
            <div class="flex items-center gap-4 flex-1">
                <progress id="quiz-progress" class="progress progress-primary w-full" value="0" max="100"></progress>
                <span id="progress-text" class="text-sm">0/0</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Quiz Container -->
            <div id="quiz-container" class="bg-base-100 shadow-xl rounded-lg">
                <!-- Loading State -->
                <div id="loading-state" class="card-body text-center">
                    <span class="loading loading-spinner loading-lg"></span>
                    <p class="mt-4">Loading quiz...</p>
                </div>

                <!-- Question Card -->
                <div id="question-card" class="card-body hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="card-title">Question <span id="current-question-number">1</span></h2>
                        <div class="badge badge-outline">
                            <span id="question-counter">1 of 1</span>
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="mb-8">
                        <div id="question-media" class="mb-4 hidden">
                            <img id="question-image" class="max-w-full h-auto rounded-lg shadow-md" alt="Question media">
                        </div>
                        <h3 id="question-text" class="text-lg font-medium mb-6"></h3>
                        
                        <!-- Choices Container -->
                        <div id="choices-container" class="space-y-3">
                            <!-- Choices will be dynamically inserted here -->
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="card-actions justify-between">
                        <button id="prev-btn" class="btn btn-outline" disabled>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous
                        </button>
                        
                        <div class="flex gap-2">
                            <button id="next-btn" class="btn btn-primary" disabled>
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <button id="submit-btn" class="btn btn-success hidden">
                                Submit Quiz
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results Card -->
                <div id="results-card" class="card-body hidden">
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="radial-progress text-primary" style="--value:0; --size:6rem;" id="score-progress">
                                <span id="score-percentage" class="text-lg font-bold">0%</span>
                            </div>
                        </div>
                        <h2 class="card-title text-2xl mb-4">Quiz Completed!</h2>
                        <div class="stats shadow mb-6">
                            <div class="stat">
                                <div class="stat-title">Score</div>
                                <div class="stat-value" id="final-score">0/0</div>
                                <div class="stat-desc" id="score-desc">0% correct</div>
                            </div>
                        </div>
                        <div class="card-actions justify-center">
                            <button id="restart-btn" class="btn btn-primary">Restart Quiz</button>
                            <button id="review-btn" class="btn btn-outline">Review Answers</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        let quizData = null;
        let currentQuestionIndex = 0;
        let userAnswers = {};
        let isReviewMode = false;

        // Initialize quiz
        loadQuiz();

        function loadQuiz() {
            // Simulate API call - replace with actual endpoint
            const quizId = {{ $id }};
            
            // Mock data based on your JSON structure
            // const mockData = {
            //     "status": "success",
            //     "data": {
            //         "quiz_id": {{ $id }},
            //         "lesson_id": 1,
            //         "title": "{{ $quiz->title ?? 'Quiz Title' }}",
            //         "description": "{{ $quiz->description ?? 'Quiz Description' }}",
            //         "choices_type": "text",
            //         "questions": [
            //             {
            //                 "question_id": 31,
            //                 "question_text": "Sample Question: What is the correct answer?",
            //                 "media_path": null,
            //                 "choices": [
            //                     {
            //                         "choice_id": 265,
            //                         "question_id": 31,
            //                         "choice_text": "Choice A - Correct Answer",
            //                         "choice_media": null,
            //                         "is_correct": 1
            //                     },
            //                     {
            //                         "choice_id": 266,
            //                         "question_id": 31,
            //                         "choice_text": "Choice B - Wrong Answer",
            //                         "choice_media": null,
            //                         "is_correct": 0
            //                     }
            //                 ]
            //             }
            //         ]
            //     }
            // };

            const token = document.querySelector('meta[name="api-token"]').content;
            
            // Make AJAX call with bearer token
            $.ajax({
                url: `/api/questions/${quizId}`,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        quizData = response.data;
                        initializeQuiz();
                    }
                },
                error: function(xhr) {
                    let message = 'Failed to load quiz data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showError(message);
                }
            });

            // Using mock data for demo
            // setTimeout(() => {
            //     quizData = mockData.data;
            //     initializeQuiz();
            // }, 1000);
        }

        function initializeQuiz() {
            $('#loading-state').hide();
            $('#question-card').show();
            
            // Reset state
            currentQuestionIndex = 0;
            userAnswers = {};
            isReviewMode = false;
            
            displayQuestion();
            updateProgress();
        }

        function displayQuestion() {
            if (!quizData || !quizData.questions) return;

            const question = quizData.questions[currentQuestionIndex];
            const totalQuestions = quizData.questions.length;

            // Update question info
            $('#current-question-number').text(currentQuestionIndex + 1);
            $('#question-counter').text(`${currentQuestionIndex + 1} of ${totalQuestions}`);
            $('#question-text').text(question.question_text);

            // Handle question media
            if (question.media_path) {
                $('#question-image').attr('src', question.media_path);
                $('#question-media').show();
            } else {
                $('#question-media').hide();
            }

            // Clear and populate choices
            const choicesContainer = $('#choices-container');
            choicesContainer.empty();

            question.choices.forEach((choice, index) => {
                const isSelected = userAnswers[question.question_id] === choice.choice_id;
                const isCorrect = choice.is_correct == 1;
                
                let choiceClass = 'input-bordered';
                let labelClass = 'cursor-pointer label p-4 rounded-lg border transition-all hover:bg-base-200';
                
                if (isReviewMode) {
                    if (isCorrect) {
                        labelClass += ' border-success bg-success/10';
                    } else if (isSelected && !isCorrect) {
                        labelClass += ' border-error bg-error/10';
                    }
                } else if (isSelected) {
                    labelClass += ' border-primary bg-primary/10';
                }

                const choiceHtml = `
                    <label class="${labelClass}">
                        <input type="radio" 
                               name="question_${question.question_id}" 
                               value="${choice.choice_id}"
                               class="radio radio-primary mr-3" 
                               ${isSelected ? 'checked' : ''}
                               ${isReviewMode ? 'disabled' : ''}>
                        <span class="label-text flex-1 text-left">
                            ${choice.choice_text}
                            ${isReviewMode && isCorrect ? '<span class="badge badge-success ml-2">Correct</span>' : ''}
                        </span>
                    </label>
                `;
                
                choicesContainer.append(choiceHtml);
            });

            // Update navigation buttons
            updateNavigationButtons();
        }

        function updateNavigationButtons() {
            const totalQuestions = quizData.questions.length;
            const isLastQuestion = currentQuestionIndex === totalQuestions - 1;
            const isFirstQuestion = currentQuestionIndex === 0;
            const hasAnswer = userAnswers[quizData.questions[currentQuestionIndex].question_id];

            $('#prev-btn').prop('disabled', isFirstQuestion);
            
            if (isReviewMode) {
                $('#next-btn').toggle(!isLastQuestion);
                $('#submit-btn').hide();
            } else {
                $('#next-btn').prop('disabled', !hasAnswer).toggle(!isLastQuestion);
                $('#submit-btn').toggle(isLastQuestion && hasAnswer);
            }
        }

        function updateProgress() {
            const totalQuestions = quizData.questions.length;
            const answeredQuestions = Object.keys(userAnswers).length;
            const progress = (answeredQuestions / totalQuestions) * 100;
            
            $('#quiz-progress').val(progress);
            $('#progress-text').text(`${answeredQuestions}/${totalQuestions}`);
        }

        // Event Listeners
        $(document).on('change', 'input[type="radio"]', function() {
            if (isReviewMode) return;
            
            const questionId = quizData.questions[currentQuestionIndex].question_id;
            const choiceId = parseInt($(this).val());
            
            userAnswers[questionId] = choiceId;
            updateNavigationButtons();
            updateProgress();
        });

        $('#next-btn').click(function() {
            if (currentQuestionIndex < quizData.questions.length - 1) {
                currentQuestionIndex++;
                displayQuestion();
            }
        });

        $('#prev-btn').click(function() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                displayQuestion();
            }
        });

        $('#submit-btn').click(function() {
            if (confirm('Are you sure you want to submit your quiz?')) {
                submitQuiz();
            }
        });

        $('#restart-btn').click(function() {
            $('#results-card').hide();
            initializeQuiz();
        });

        $('#review-btn').click(function() {
            isReviewMode = true;
            currentQuestionIndex = 0;
            $('#results-card').hide();
            $('#question-card').show();
            displayQuestion();
        });

        function submitQuiz() {
            // Calculate results
            let correctAnswers = 0;
            const totalQuestions = quizData.questions.length;

            quizData.questions.forEach(question => {
                const userAnswer = userAnswers[question.question_id];
                const correctChoice = question.choices.find(c => c.is_correct == 1);
                
                if (userAnswer === correctChoice.choice_id) {
                    correctAnswers++;
                }
            });

            const percentage = Math.round((correctAnswers / totalQuestions) * 100);

            // Show results
            $('#question-card').hide();
            $('#results-card').show();
            
            $('#score-progress').css('--value', percentage);
            $('#score-percentage').text(`${percentage}%`);
            $('#final-score').text(`${correctAnswers}/${totalQuestions}`);
            $('#score-desc').text(`${percentage}% correct`);

            // Here you would typically send results to server
            /*
            $.ajax({
                url: '/api/quiz-results',
                method: 'POST',
                data: {
                    quiz_id: quizData.quiz_id,
                    answers: userAnswers,
                    score: correctAnswers,
                    total: totalQuestions
                },
                success: function(response) {
                    console.log('Results saved');
                }
            });
            */
        }

        function showError(message) {
            $('#loading-state').html(`
                <div class="alert alert-error">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${message}</span>
                </div>
                <button class="btn btn-primary mt-4" onclick="location.reload()">Retry</button>
            `);
        }
    });
    </script>
</x-app-layout>