/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.addQuestion = addQuestion;
window.updateQuestionsForChoicesType = updateQuestionsForChoicesType;
window.removeQuestion = removeQuestion;
window.addChoice = addChoice;
window.removeChoice = removeChoice;
window.updateChoiceIndexes = updateChoiceIndexes;
window.updateChoiceRemoveButtons = updateChoiceRemoveButtons;
window.navigateToQuestion = navigateToQuestion;
window.prevQuestion = prevQuestion;
window.nextQuestion = nextQuestion;
window.updateQuestionCounter = updateQuestionCounter;
// onchange event for #quiz-choices-type

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

	let questionHeaderHtml;
	if (choicesType === 'media') {
		questionHeaderHtml = `
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend"><span class="font-medium text-lg">Question Text</span></legend>
                        <input type="text" id="question-text-${questionId}" class="w-full input input-bordered validator" placeholder="Enter question text...">
                        <p id="question-text-val-${questionId}" class="hidden text-error text-xs mt-1">Question text is required.</p>
                    </fieldset>
                `
	} else {
		questionHeaderHtml = `
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend"><span class="font-medium text-lg">Media</span></legend>
                        <input type="file" id="question-media-${questionId}" class="w-full file-input file-input-bordered"
                            accept="image/*,video/*" onchange="previewMedia(this, '${questionId}')">
                            <p id="question-media-val-${questionId}" class="hidden text-error text-xs mt-1">Question media is required.</p>
                        <div class="media-preview mt-4 hidden" id="media-preview-${questionId}"></div>
                    </fieldset>
                `
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

                        <div class="question-header-container" id="question-header-${questionId}">
                            ${questionHeaderHtml}
                        </div>

                        <fieldset class="fieldset">

                            <legend class="fieldset-legend"><span class="font-medium text-lg">Answer Choices</span></legend>
                            <p id="choice-radio-val-${questionId}" class="text-error hidden text-xs mb-2">
                                Select a correct answer.
                            </p>
                            <div class="choices-container space-y-3" id="choices-${questionId}">
                                <div class="choice-item flex-col p-3 bg-base-100 border border-base-300 rounded-lg shadow-lg">
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

                                <div class="choice-item flex-col p-3 bg-base-100 border border-base-300 rounded-lg shadow-lg">
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

		let questionHeaderHtml;
		if (newType === 'media') {
			questionHeaderHtml = `
                        <legend class="fieldset-legend"><span class="font-medium text-lg">Question Text</span></legend>
                        <input type="text" id="question-text-${questionId}" class="w-full input input-bordered validator" placeholder="Enter question text...">
                        <p id="question-text-val-${questionId}" class="hidden text-error text-xs mt-1">Question text is required.</p>
                    `
		} else {
			questionHeaderHtml = `
                        <legend class="fieldset-legend"><span class="font-medium text-lg">Media</span></legend>
                        <input type="file" id="question-media-${questionId}" class="w-full file-input file-input-bordered"
                            accept="image/*,video/*" onchange="previewMedia(this, '${questionId}')">
                            <p id="question-media-val-${questionId}" class="hidden text-error text-xs mt-1">Question media is required.</p>
                        <div class="media-preview mt-4 hidden" id="media-preview-${questionId}"></div>
                    `
		}

		const $questionHeaderContainer = $questionCard.find(`#question-header-${questionId}`);
		$questionHeaderContainer.empty();
		$questionHeaderContainer.append(questionHeaderHtml);

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
                        <div class="choice-item flex-col p-3 bg-base-100 border border-base-300 rounded-lg shadow-lg">
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
	choicesContainer.children().each(function (index) {
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

function updateQuestionCounter() {
	$('#current-question-num').text(currentQuestionIndex + 1);
	$('#total-questions').text(questions.length);
}