<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row gap-12">
            <!-- Left: title & description -->
            <div class="shrink-0 max-w-full sm:max-w-[50vw] space-y-1">
                <!-- Titles -->
                <p class="text-base-content/70 truncate">
                    {{ $quiz->lesson->title }}
                </p>

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

    <style>
        /* #question-card,
        #notes-card {
            max-height: 80vh;
            overflow-y: auto;
        } */

        #question-card img,
        #notes-card img {
            max-height: 35vh; /* or whatever suits you */
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
    </style>

    <div class="">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Quiz Container -->
            <div id="quiz-container" class="border border-base-300 bg-base-100 dark:bg-base-200 shadow-xl rounded-lg">
                <!-- Loading State -->
                <div id="loading-state" class="card-body text-center">
                    <span class="loading loading-spinner loading-lg"></span>
                    <p class="mt-4">Loading quiz...</p>
                </div>

                <!-- Notes Card -->
                <div id="notes-card" class="card-body text-center hidden space-y-4">
                    {{-- <h2 id="notes-title" class="card-title text-2xl mb-2"></h2> --}}
                    {{-- <p id="notes-desc" class="text-base-content/70 mb-6"></p> --}}

                    <div id="note-display" class="flex justify-center">
                        <!-- one note inserted here -->
                    </div>

                    <div class="text-sm text-base-content mt-2" id="note-counter">
                        <!-- Note X of Y -->
                    </div>

                    <div class="flex justify-center gap-4 mt-4">
                        <button id="prev-note-btn" class="btn btn-outline btn-sm">Previous</button>
                        <button id="next-note-btn" class="btn btn-outline btn-sm">Next</button>
                    </div>

                    <button id="start-quiz-btn" class="btn btn-primary mt-6">Start Quiz</button>
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
                        <div id="question-content" class="flex flex-col md:flex-row gap-12">
                            <!-- Media -->
                            <div id="question-media" class="hidden md:w-1/2 flex-shrink-0">
                                <img id="question-image" class="w-full h-auto rounded-lg shadow-md object-contain" alt="Question media">
                            </div>

                            <!-- Text & Choices -->
                            <div class="flex-1">
                                <h3 id="question-text" class="text-lg font-medium mb-6"></h3>
                                <div id="choices-container" class="space-y-3">
                                    <!-- Choices go here -->
                                </div>
                            </div>
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
                            <button id="end-quiz-btn" class="btn btn-error">
                                End Quiz
                            </button>
                            <button id="next-btn" class="btn btn-primary" disabled>
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <button id="show-results-btn" class="btn btn-success hidden">
                                Show Results
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <button id="submit-btn" class="btn btn-success">
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
                            <div id="grade-emoji" class="text-4xl mb-4">😊</div>
                            <div class="radial-progress text-primary" style="--value:0; --size:6rem;" id="score-progress">
                                <span id="score-percentage" class="text-lg font-bold">0%</span>
                            </div>
                        </div>
                        <h2 class="card-title text-2xl mb-4 justify-center">Quiz Completed!</h2>
                        <div class="stats stats-vertical lg:stats-horizontal shadow mb-6">
                            <div class="stat">
                                <div class="stat-title">Total Questions</div>
                                <div class="stat-value" id="total-questions">0</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Grade</div>
                                <div class="stat-value text-primary" id="grade">-</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Correct</div>
                                <div class="stat-value text-success" id="correct-answers">0</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Incorrect</div>
                                <div class="stat-value text-error" id="incorrect-answers">0</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Time Taken</div>
                                <div class="stat-value text-info" id="time-taken">0s</div>
                            </div>
                        </div>
                        <div class="card-actions justify-center">
                            <button id="restart-btn" class="btn btn-primary">Restart Quiz</button>
                            <button id="review-btn" class="btn btn-outline">Review Answers</button>
                            <a href="{{ route('player.quizzes.index') }}" id="finish-btn" class="btn btn-success">Finish</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentNoteIndex = 0;

    $(document).ready(function() {
        let quizData = null;
        let currentQuestionIndex = 0;
        let userAnswers = {};
        let isReviewMode = false;
        let quizStartTime = null;

        // Initialize quiz
        loadQuiz();

        function loadQuiz() {
            const quizId = {{ $id }};
            const token = document.querySelector('meta[name="api-token"]').content;

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
                        showNotes(); // show notes before starting
                    }
                },
                error: function(xhr) {
                    let message = 'Failed to load quiz data';
                    if (xhr.responseJSON?.message) message = xhr.responseJSON.message;
                    showError(message);
                }
            });
        }

        $(document).on('change', 'input[type="radio"]', function () {
            if (isReviewMode) return;

            const questionId = quizData.questions[currentQuestionIndex].question_id;
            const choiceId = parseInt($(this).val());
            userAnswers[questionId] = choiceId;

            const question = quizData.questions[currentQuestionIndex];
            const correctChoice = question.choices.find(c => c.is_correct == 1);

            // Lock all choices
            $(`input[name="question_${questionId}"]`).prop('disabled', true);

            // Mark all choices
            $(`input[name="question_${questionId}"]`).each(function () {
                const $label = $(this).closest('label');
                const thisChoiceId = parseInt($(this).val());

                if (thisChoiceId === correctChoice.choice_id) {
                    $label.addClass('border-success bg-success/10');
                }

                if (thisChoiceId === choiceId && thisChoiceId !== correctChoice.choice_id) {
                    $label.addClass('border-error bg-error/10');
                }
            });

            updateNavigationButtons();
            updateProgress();
        });

        function showNotes() {
            $('#loading-state').hide();
            $('#notes-card').show();

            // $('#notes-title').text(quizData.title);
            // $('#notes-desc').text(quizData.description);

            currentNoteIndex = 0;

            renderCurrentNote();
            updateNoteNavButtons();
        }

        function renderCurrentNote() {
            const note = quizData.notes[currentNoteIndex];

            const isVideo = /\.(mp4|webm|ogg)$/i.test(note.note_media);

            const noteHtml = `
                <div class="card bg-base-100 shadow-md max-w-md">
                    <figure class="px-4 pt-4">
                        ${isVideo 
                            ? `<video controls class="rounded-lg shadow max-h-[35vh] mx-auto">
                                    <source src="/storage/${note.note_media}" type="video/mp4">
                                    Your browser does not support the video tag.
                            </video>`
                            : `<img src="/storage/${note.note_media}" alt="Note Media" class="rounded-lg shadow max-h-[35vh] mx-auto object-contain">`}
                    </figure>
                    <div class="card-body">
                        <p class="text-base-content">${note.note_text}</p>
                    </div>
                </div>
            `;

            $('#note-display').html(noteHtml);

            // update note counter
            $('#note-counter').text(
                `Note ${currentNoteIndex + 1} of ${quizData.notes.length}`
            );
        }

        function updateNoteNavButtons() {
            $('#prev-note-btn').prop('disabled', currentNoteIndex === 0);
            $('#next-note-btn').prop('disabled', currentNoteIndex === quizData.notes.length - 1);
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

        $('#prev-note-btn').click(function () {
            if (currentNoteIndex > 0) {
                currentNoteIndex--;
                renderCurrentNote();
                updateNoteNavButtons();
            }
        });

        $('#next-note-btn').click(function () {
            if (currentNoteIndex < quizData.notes.length - 1) {
                currentNoteIndex++;
                renderCurrentNote();
                updateNoteNavButtons();
            }
        });

        $('#start-quiz-btn').click(function () {
            $('#notes-card').hide();
            initializeQuiz();
        });


        function displayQuestion() {
            quizStartTime = Date.now();

            if (!quizData || !quizData.questions) return;

            const question = quizData.questions[currentQuestionIndex];
            const totalQuestions = quizData.questions.length;

            $('#current-question-number').text(currentQuestionIndex + 1);
            $('#question-counter').text(`${currentQuestionIndex + 1} of ${totalQuestions}`);
            $('#question-text').text(question.question_text);

            console.log('Displaying question:', question.question_text);

            if (question.media_path) {
                const isVideo = /\.(mp4|webm|ogg)$/i.test(question.media_path);

                const mediaHtml = isVideo 
                    ? `<video controls class="w-full h-auto rounded-lg shadow-md object-contain">
                            <source src="/storage/${question.media_path}" type="video/mp4">
                            Your browser does not support the video tag.
                    </video>`
                    : `<img src="/storage/${question.media_path}" alt="Question media" class="w-full h-auto rounded-lg shadow-md object-contain">`;

                $('#question-media').html(mediaHtml).show();
            } else {
                $('#question-media').hide().empty();
            }

            // choices display breakpoints, 2 choices = 1 column, 3-4 choices = 2 columns, 5+ choices = 3 columns
            const choicesContainer = $('#choices-container');
            choicesContainer.empty();

            // count choices
            const choiceCount = question.choices.length;

            // decide layout
            let layoutClass = 'grid gap-4';

            if (choiceCount <= 2) {
                layoutClass += ' grid-cols-1';
            } else if (choiceCount <= 4) {
                layoutClass += ' grid-cols-2';
            } else {
                layoutClass += ' md:grid-cols-3 sm:grid-cols-2 grid-cols-1';
            }

            choicesContainer.attr('class', layoutClass);

            question.choices.forEach(choice => {
                const isSelected = userAnswers[question.question_id] === choice.choice_id;
                const isCorrect = choice.is_correct == 1;

                let labelClass =
                    'cursor-pointer label p-4 rounded-lg border transition-all hover:bg-base-200';
                if (isReviewMode) {
                    if (isCorrect) {
                        labelClass += ' border-success bg-success/10';
                    } else if (isSelected && !isCorrect) {
                        labelClass += ' border-error bg-error/10';
                    }
                } else if (isSelected) {
                    labelClass += ' border-primary bg-primary/10';
                }

                // build choice content
                let choiceContent = '';
                if (choice.choice_text) {
                    choiceContent += `<span>${choice.choice_text}</span>`;
                }
                if (choice.choice_media) {
                    const isVideo = /\.(mp4|webm|ogg)$/i.test(choice.choice_media);

                    choiceContent += isVideo 
                        ? `<video controls class="rounded-lg shadow-md mt-2 max-h-[30vh] object-contain">
                                <source src="/storage/${choice.choice_media}" type="video/mp4">
                                Your browser does not support the video tag.
                        </video>`
                        : `<img id="choice-media" src="/storage/${choice.choice_media}" 
                            alt="Choice media" 
                            class="rounded-lg shadow-md mt-2 object-contain">`;
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
                            ${choiceContent}
                            ${isReviewMode && isCorrect ? '<span class="badge badge-success ml-2">Correct</span>' : ''}
                        </span>
                    </label>
                `;
                choicesContainer.append(choiceHtml);
            });

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
                $('#show-results-btn').removeClass('hidden');
            } else {
                $('#next-btn').prop('disabled', !hasAnswer).toggle(!isLastQuestion);

                const totalQuestions = quizData.questions.length;
                const answeredQuestions = Object.keys(userAnswers).length;

                if (answeredQuestions === totalQuestions) {
                    $('#submit-btn').show();
                } else {
                    $('#submit-btn').hide();
                }

                $('#show-results-btn').addClass('hidden');
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

        $('#show-results-btn').click(function () {
            $('#question-card').hide();
            $('#results-card').show();
        });

        $('#end-quiz-btn').click(function () {
            if (confirm('Are you sure you want to end the quiz? Your progress will not be saved.')) {
                window.location.href = '{{ route("dashboard") }}';
            }
        });

        function submitQuiz() {
            // Calculate results
            let correctAnswers = 0;
            const totalQuestions = quizData.questions.length;
            const quizEndTime = Date.now();
            const durationSeconds = Math.floor((quizEndTime - quizStartTime) / 1000);

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
            
            // Update progress circle and percentage
            $('#score-progress').css('--value', percentage);
            $('#score-percentage').text(`${percentage}%`);

            // Update statistics
            $('#total-questions').text(totalQuestions);
            $('#correct-answers').text(correctAnswers);
            $('#incorrect-answers').text(totalQuestions - correctAnswers);
            $('#time-taken').text(`${durationSeconds}s`);

            // Determine grade and emoji
            let grade;
            let emoji;
            if (percentage >= 80) {
                grade = 'A';
                emoji = '😊';
            } else if (percentage >= 60) {
                grade = 'B';
                emoji = '😐';
            } else {
                grade = 'C';
                emoji = '☹️';
            }

            // Update grade and emoji
            $('#grade').text(grade);
            $('#grade-emoji').text(emoji);

            const lessonId = quizData.lesson_id;    // assuming you include lesson_id in `quizData`
            const quizId = quizData.quiz_id;
            const token = document.querySelector('meta[name="api-token"]').content;

            console.log('Submitting results:', {
                lessonId,
                quizId,
                userAnswers,
                correctAnswers,
                totalQuestions,
                durationSeconds
            });

            $.ajax({
                url: `/api/lessons/${lessonId}/questions/${quizId}/submit`,
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                data: {
                    lesson_id: lessonId,
                    quiz_id: quizId,
                    answers: userAnswers,
                    score: correctAnswers,
                    total: totalQuestions,
                    duration_seconds: durationSeconds
                },
                success: function(response) {
                    console.log('Results saved', response);
                },
                error: function(xhr) {
                    console.error('Failed to save results', xhr.responseJSON);
                }
            });

            // Calculate exp gained
            const expGained = correctAnswers * 10;

            // Submit EXP after results are saved
            $.ajax({
                url: `/api/player/exp`,
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                data: {
                    lesson_id: lessonId,
                    exp: expGained
                },
                success: function(response) {
                    console.log('EXP added', response);
                },
                error: function(xhr) {
                    console.error('Failed to add EXP', xhr.responseJSON);
                }
            });
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