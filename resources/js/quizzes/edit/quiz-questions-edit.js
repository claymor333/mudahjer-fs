/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.addQuestion = addQuestion;
window.renderChoices = renderChoices;
window.renderChoiceInput = renderChoiceInput;
window.updateQuestionsForChoicesType = updateQuestionsForChoicesType;
window.addChoice = addChoice;
window.removeChoice = removeChoice;
window.updateChoiceIndexes = updateChoiceIndexes;
window.updateChoiceRemoveButtons = updateChoiceRemoveButtons;
window.navigateToQuestion = navigateToQuestion;
window.prevQuestion = prevQuestion;
window.nextQuestion = nextQuestion;
window.updateQuestionCounter = updateQuestionCounter;
window.updateQuestionSequences = updateQuestionSequences;
window.removeQuestion = removeQuestion;


function addQuestion(existingId = null, questionText = '', mediaPath = null, choices = []) {
	const nextNumber = questions.length + 1;
	const questionId = existingId ? `question-${existingId}` : `question-${Date.now()}`;
	const choicesType = $('#quiz-choices-type').val();

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

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Question Text</legend>
                        <input type="text" id="question-text-${questionId}" class="w-full input input-bordered validator"
                            placeholder="Enter question text..." value="${questionText || ''}" required>
                        <p id="question-text-val-${questionId}" class="hidden text-error text-xs mt-1">Question text is required.</p>

                        <legend class="fieldset-legend mt-4">Media (Optional)</legend>
                        <input type="file" id="question-media-${questionId}" class="w-full file-input file-input-bordered"
                            accept="image/*,video/*" onchange="previewMedia(this, '${questionId}')">
                        <div class="media-preview mt-2" id="media-preview-${questionId}">
                            ${mediaPath ? renderMediaPreview(mediaPath, questionId) : ''}
                        </div>

                        <legend class="fieldset-legend mt-4">Answer Choices</legend>
                        <p id="choice-radio-val-${questionId}" class="text-error hidden text-xs mb-2">
                            Select a correct answer.
                        </p>
                        <div class="choices-container space-y-3" id="choices-${questionId}">
                            ${renderChoices(choices, choicesType, questionId, nextNumber - 1)}
                        </div>

                        <button type="button" class="btn btn-sm btn-outline btn-primary mt-3" onclick="addChoice('${questionId}', '${choicesType}')">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

	if (!existingId) {
		navigateToQuestion(questions.length - 1);
		updateQuestionCounter();
		updateNavigationButtons();
	}

	return questionId;
}

/**
 * Render the choices HTML
 * @param {Array} choices
 * @param {string} choicesType
 * @param {string} questionId
 * @returns {string}
 */
function renderChoices(choices, choicesType, questionId, questionIndex) {

	console.log(choices, choicesType, questionId, questionIndex);
	if (!choices || !choices.length) {
		choices = [
			{ id: null, choice_text: '', choice_media: '', is_correct: false },
			{ id: null, choice_text: '', choice_media: '', is_correct: false }
		];
	}

	return choices.map((choice, index) => {
		const isChecked = choice.is_correct ? 'checked' : '';

		const value = choicesType === 'media'
			? choice.choice_media || ''
			: choice.choice_text || '';

		return `
                <div class="choice-item flex-col p-3 bg-base-100 rounded-lg" ${choice.id ? `data-choice-id="${choice.id}"` : ''}>
                    <div class="flex items-center gap-3">
                        <input type="radio" name="questions[${questionIndex}][correct_choice]" class="radio radio-primary" value="${index}" ${isChecked}>
                        ${renderChoiceInput(value, choicesType)}
                        <button type="button" class="btn btn-sm btn-circle btn-ghost opacity-50" onclick="removeChoice(this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="choice-validator-input text-error hidden text-xs mt-1"></p>
                </div>`;
	}).join('');
}

/**
 * Render a single choice input depending on type
 * @param {string} value
 * @param {string} type - text|media
 * @returns {string}
 */
function renderChoiceInput(value, type) {
	if (type === 'media') {
		const hasMedia = value && value.trim() !== '';
		const src = hasMedia ? `/storage/${value}` : '';
		const isVideo = hasMedia && /\.(mp4|webm|ogg)$/i.test(value);
		const isImage = hasMedia && /\.(jpg|jpeg|png|gif|bmp|svg|webp)$/i.test(value);

		console.log('Rendering choice input:', { value, type, hasMedia, isVideo, isImage });

		return `
                <div class="indicator w-full relative">
                    <!-- Image Badge -->
                    <span class="indicator-item choice-media choice-preview-img !p-0 badge badge-secondary w-8 h-8 overflow-hidden ${isImage ? '' : 'hidden'}">
                        <img 
                        src="${src}" 
                        data-media-src="${src}" 
                        data-media-type="image" 
                        alt="Image Preview" 
                        class="w-full h-full object-cover cursor-pointer" 
                        onchange="handleMediaPreview(this)" 
                        onclick="showPreviewModal(this)">
                    </span>

                    <!-- Video Badge -->
                    <span 
                        class="indicator-item choice-media cursor-pointer choice-preview-video !p-0 badge badge-secondary w-8 h-8 flex items-center justify-center ${isVideo ? '' : 'hidden'}"
                        data-media-type="video" 
                        data-media-src="${src}" 
                        onclick="showPreviewModal(this)">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4.5 3.5a1 1 0 011.6-.8l10 7a1 1 0 010 1.6l-10 7A1 1 0 014.5 17V3.5z" />
                        </svg>
                    </span>

                        <input 
                        type="file" 
                        class="file-input file-input-bordered flex-1 validator choice-media-input" 
                        accept="image/*,video/*" 
                        onchange="handleMediaPreview(this)"
                        ${hasMedia ? '' : 'required'}>
                        ${hasMedia ? `<input type="hidden" name="existing_choice_media" value="${value}">` : ''}
                    </div>`;
	}

	// text choice
	return `
            <input type="text" class="input input-bordered flex-1 validator" placeholder="Choice text" value="${value || ''}" required>`;
}

/**
 * Update all questions when choices_type changes
 * @param {string} newType
 */
function updateQuestionsForChoicesType(newType) {
	questions.forEach((questionId, index) => {
		const $questionCard = $(`#${questionId}`);

		const $choicesContainer = $questionCard.find(`#choices-${questionId}`);
		$choicesContainer.empty();

		const defaultChoices = [
			{ id: null, choice_text: '', is_correct: false },
			{ id: null, choice_text: '', is_correct: false }
		];

		const newChoicesHtml = renderChoices(defaultChoices, newType, questionId, index);
		$choicesContainer.html(newChoicesHtml);
	});
}

function addChoice(questionId, choiceType) {
	const questionCard = $(`#${questionId}`);
	const questionIndex = parseInt(questionCard.data('sequence')) - 1;
	const choicesContainer = $(`#choices-${questionId}`);
	const choiceCount = choicesContainer.children().length;

	const choiceInput = renderChoiceInput('', choiceType);

	const choiceHtml = `
                <div class="choice-item flex items-center gap-3 p-3 bg-base-100 rounded-lg">
                    <input type="hidden" name="questions[${questionIndex}][choices][${choiceCount}][id]" value="">
                    <input type="radio" name="questions[${questionIndex}][correct_choice]" class="radio radio-primary" value="${choiceCount}">
                    <div class="flex-1">${choiceInput}</div>
                    <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeChoice(this)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

	const $newChoice = $(choiceHtml);
	choicesContainer.append($newChoice);

	// Focus the text input or file input of the new choice
	const $input = $newChoice.find('input[type="text"], input[type="file"]');
	$input.first().focus();

	// Scroll to the new choice
	const currentScroll = window.scrollY;
	const choiceHeight = $newChoice.outerHeight();
	window.scrollTo({
		top: currentScroll + choiceHeight + 12,
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

function updateQuestionSequences() {
	questions.forEach((qId, index) => {
		const questionCard = $(`#${qId}`);
		const newSequence = index + 1;

		questionCard
			.attr('data-sequence', newSequence)
			.find('h3')
			.text(`Question ${newSequence}`);

		// Fix radio input names in choices
		questionCard.find('.choice-item input[type="radio"]').each(function () {
			this.name = `questions[${index}][correct_choice]`;
		});
	});
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
	questionCard.fadeOut(300, function () {
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