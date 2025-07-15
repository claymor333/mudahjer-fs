<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Create New Quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 shadow-xl rounded-box p-8">
                <ul class="steps steps-horizontal w-full mb-8">
                    <li class="step step-primary" data-content="1" id="step-1">Quiz Details</li>
                    <li class="step" data-content="2" id="step-2">Questions</li>
                </ul>

                <form id="quizForm" action="{{ route('admin.quizzes.store-wizard') }}" method="POST" enctype="multipart/form-data">
					{{-- CSRF Token --}}
                    @csrf
                    
                    <!-- Step 1: Quiz Details -->
                    <div id="quiz-details" class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Quiz Title</span>
                            </label>
                            <input type="text" name="title" class="input input-bordered" required />
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Description</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered" required></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                        </div>
                    </div>

                    <!-- Step 2: Questions -->
                    <div id="questions" class="hidden">
                        <div id="questions-container" class="space-y-8">
                            <!-- Questions will be added here -->
                        </div>
                        
                        <div class="mt-4 space-x-2">
                            <button type="button" class="btn btn-secondary" onclick="addQuestion()">
                                Add Question
                            </button>
                            <button type="button" class="btn" onclick="previousStep()">Previous</button>
                            <button type="submit" class="btn btn-primary">Create Quiz</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentStep = 1;
        let questionCount = 0;

        function nextStep() {
            if (currentStep === 1) {
                if (!document.querySelector('[name="title"]').value || 
                    !document.querySelector('[name="description"]').value) {
                    alert('Please fill in all fields');
                    return;
                }
                document.getElementById('quiz-details').classList.add('hidden');
                document.getElementById('questions').classList.remove('hidden');
                document.getElementById('step-2').classList.add('step-primary');
                if (questionCount === 0) addQuestion();
                currentStep = 2;
            }
        }

        function previousStep() {
            if (currentStep === 2) {
                document.getElementById('questions').classList.add('hidden');
                document.getElementById('quiz-details').classList.remove('hidden');
                document.getElementById('step-2').classList.remove('step-primary');
                currentStep = 1;
            }
        }

        function addQuestion() {
            questionCount++;
            const questionHtml = `
                <div class="card bg-base-200 shadow-lg" id="question-${questionCount}">
                    <div class="card-body">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Question ${questionCount}</span>
                                <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeQuestion(${questionCount})">✕</button>
                            </label>
                            <input type="text" name="questions[${questionCount}][question_text]" class="input input-bordered" required placeholder="Enter question text">
                            
                            <!-- Media Upload -->
                            <label class="label mt-2">
                                <span class="label-text">Media (optional)</span>
                            </label>
                            <input type="file" name="questions[${questionCount}][media]" 
                                class="file-input file-input-bordered w-full" 
                                accept="image/*,video/*"
                                onchange="previewMedia(this, ${questionCount})" />
                            <div id="media-preview-${questionCount}" class="mt-2"></div>
                        </div>
                        
                        <div class="choices-container space-y-2 mt-4">
                            <label class="label">
                                <span class="label-text">Choices</span>
                            </label>
                            <div class="choice-inputs space-y-2">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="questions[${questionCount}][correct_choice]" value="0" class="radio" required>
                                    <input type="text" name="questions[${questionCount}][choices][]" class="input input-bordered w-full" required placeholder="Choice 1">
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="questions[${questionCount}][correct_choice]" value="1" class="radio">
                                    <input type="text" name="questions[${questionCount}][choices][]" class="input input-bordered w-full" required placeholder="Choice 2">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-ghost" onclick="addChoice(${questionCount})">+ Add Choice</button>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHtml);
        }

        function previewMedia(input, questionId) {
            const preview = document.getElementById(`media-preview-${questionId}`);
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const isVideo = file.type.startsWith('video/');
                
                if (isVideo) {
                    const video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.className = 'w-48 h-48 object-cover rounded-lg';
                    video.controls = true;
                    preview.appendChild(video);
                } else {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'w-48 h-48 object-cover rounded-lg';
                    preview.appendChild(img);
                }
            }
        }

        function addChoice(questionId) {
            const choiceCount = document.querySelectorAll(`#question-${questionId} .choice-inputs > div`).length;
            const choiceHtml = `
                <div class="flex items-center gap-2">
                    <input type="radio" name="questions[${questionId}][correct_choice]" value="${choiceCount}" class="radio">
                    <input type="text" name="questions[${questionId}][choices][]" class="input input-bordered w-full" required placeholder="Choice ${choiceCount + 1}">
                </div>
            `;
            document.querySelector(`#question-${questionId} .choice-inputs`).insertAdjacentHTML('beforeend', choiceHtml);
        }

        function removeQuestion(questionId) {
            document.getElementById(`question-${questionId}`).remove();
        }
    </script>
    @endpush
</x-app-layout>
