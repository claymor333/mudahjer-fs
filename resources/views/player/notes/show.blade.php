<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row gap-4 justify-center text-center">
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
        #note-display img,
        #note-display video {
            max-height: 35vh;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
    </style>

    <div class="">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div id="quiz-container" class="border border-base-300 bg-base-100 dark:bg-base-200 shadow-xl rounded-lg">
                <div id="notes-card" class="card-body text-center space-y-4">
                    <div id="note-display" class="flex justify-center"></div>

                    <div class="text-sm text-base-content mt-2" id="note-counter"></div>

                    <div class="flex justify-center gap-4 mt-4">
                        <button id="prev-note-btn" class="btn btn-outline btn-sm">Previous</button>
                        <button id="next-note-btn" class="btn btn-outline btn-sm">Next</button>
                    </div>

                    <a href="{{ route('player.notes.index') }}" id="start-quiz-btn" class="btn btn-primary mt-6">Finish</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let notes = @json($quiz->notes);
        let currentNoteIndex = 0;

        function renderCurrentNote() {
            const note = notes[currentNoteIndex];

            // Determine file extension and type
            const ext = note.media_path?.split('.').pop().toLowerCase();
            const isVideo = ['mp4', 'webm', 'ogg'].includes(ext);

            let mediaHtml = '';

            if (note.media_path) {
                const src = `/storage/${note.media_path}`;
                if (isVideo) {
                    mediaHtml = `<video controls autoplay muted>
                                    <source src="${src}" type="video/${ext}">
                                    Your browser does not support the video tag.
                                 </video>`;
                } else {
                    mediaHtml = `<img src="${src}" alt="Note Media">`;
                }
            }

            const html = `
                <div class="card bg-base-100 shadow-md max-w-md">
                    <figure class="px-4 pt-4">
                        ${mediaHtml}
                    </figure>
                    <div class="card-body">
                        <p class="text-base-content">${note.note_text}</p>
                    </div>
                </div>
            `;

            const display = document.getElementById('note-display');
            display.innerHTML = html;

            // Reset media to avoid stuck video
            display.querySelectorAll('video').forEach(v => {
                v.load();
            });

            document.getElementById('note-counter').textContent =
                `Note ${currentNoteIndex + 1} of ${notes.length}`;

            updateNoteNavButtons();
        }

        function updateNoteNavButtons() {
            document.getElementById('prev-note-btn').disabled = currentNoteIndex === 0;
            document.getElementById('next-note-btn').disabled = currentNoteIndex === notes.length - 1;
        }

        document.getElementById('prev-note-btn').addEventListener('click', () => {
            if (currentNoteIndex > 0) {
                currentNoteIndex--;
                renderCurrentNote();
            }
        });

        document.getElementById('next-note-btn').addEventListener('click', () => {
            if (currentNoteIndex < notes.length - 1) {
                currentNoteIndex++;
                renderCurrentNote();
            }
        });

        // Initial render
        renderCurrentNote();
    </script>
</x-app-layout>
