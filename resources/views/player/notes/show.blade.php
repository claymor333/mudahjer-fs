<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row gap-4 justify-center text-center">
            <!-- Title & Description -->
            <div class="space-y-1 max-w-full sm:max-w-[70vw]">
                <p class="text-base-content/70 truncate">
                    {{ $quiz->lesson->title }}
                </p>

                <h2 class="font-semibold text-xl text-base-content mb-1 truncate">
                    {{ $quiz->title }}
                </h2>

                <p class="text-base-content/70 mb-2 truncate">
                    {{ $quiz->description }}
                </p>
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

                    <a href="{{ route('dashboard') }}" id="start-quiz-btn" class="btn btn-primary mt-6">Finish</a>
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

            const noteHtml = `
                <div class="card bg-base-100 shadow-md max-w-md">
                    <figure class="px-4 pt-4">
                        <img src="/storage/${note.note_media}" alt="Note Media" class="rounded-lg shadow">
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

        function updateProgress() {
            const totalQuestions = quizData.questions.length;
            const answeredQuestions = Object.keys(userAnswers).length;
            const progress = (answeredQuestions / totalQuestions) * 100;
            
            $('#quiz-progress').val(progress);
            $('#progress-text').text(`${answeredQuestions}/${totalQuestions}`);
        }
    });
    </script>
</x-app-layout>