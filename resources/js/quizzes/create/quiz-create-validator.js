/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.validateStep = validateStep;

function validateStep(step) {
	let isValid = true;
	let firstInvalidElement = null;
	let firstErrorIndex = null;
	let errorType = null;
	const choicesType = $('#quiz-choices-type').val();

	if (step === 1) {
		// Validate Title
		const title = $('#quiz-title').val().trim();
		if (!title) {
			$('#quiz-title').addClass('input-error');
			$('#quiz-title-val').removeClass('hidden');
			if (!firstInvalidElement) firstInvalidElement = $('#quiz-title')[0];
			isValid = false;
		} else {
			$('#quiz-title').removeClass('input-error');
			$('#quiz-title-val').addClass('hidden');
		}

		// Validate Description
		const description = $('#quiz-description').val().trim();
		if (!description) {
			$('#quiz-description').addClass('input-error');
			$('#quiz-description-val').removeClass('hidden');
			if (!firstInvalidElement) firstInvalidElement = $('#quiz-description')[0];
			isValid = false;
		} else {
			$('#quiz-description').removeClass('input-error');
			$('#quiz-description-val').addClass('hidden');
		}

		// Validate Lesson
		const lessonVal = $('#quiz-lesson').val();
		if (!lessonVal) {
			$('#quiz-lesson').addClass('input-error');
			$('#quiz-lesson-val').removeClass('hidden');
			if (!firstInvalidElement) firstInvalidElement = $('#quiz-lesson')[0];
			isValid = false;
		} else {
			$('#quiz-lesson').removeClass('input-error');
			$('#quiz-lesson-val').addClass('hidden');
		}
	} else if (step === 2) {
		notes.forEach((noteId, index) => {
			const noteTextInput = $(`#note-text-${noteId}`);
			const noteMediaInput = $(`#note-media-${noteId}`);

			const noteText = noteTextInput.val().trim();
			const mediaFile = noteMediaInput[0]?.files[0];

			if (!noteText) {
				noteTextInput.addClass('input-error');
				$(`#note-text-val-${noteId}`).removeClass('hidden');
				if (!firstInvalidElement) {
					firstInvalidElement = noteTextInput[0];
					firstErrorIndex = index;
					errorType = 'note';
				}
				isValid = false;
			}

			if (!mediaFile) {
				noteMediaInput.addClass('input-error');
				$(`#note-media-val-${noteId}`).removeClass('hidden');
				if (!firstInvalidElement) {
					firstInvalidElement = noteMediaInput[0];
					firstErrorIndex = index;
					errorType = 'note';
				}
				isValid = false;
			}
		});
	} else if (step === 3) {
		questions.forEach((questionId, index) => {
			console.log('Validating question', questionId, 'with type:', choicesType);
			// Question text or media validation
			if (choicesType === 'media') {
				const questionInput = $(`#question-text-${questionId}`);
				const questionText = questionInput.val().trim();

				if (!questionText) {
					questionInput.addClass('input-error');
					$(`#question-text-val-${questionId}`).removeClass('hidden');
					if (!firstInvalidElement) {
						firstInvalidElement = questionInput[0];
						firstErrorIndex = index;
						errorType = 'question';
					}
					isValid = false;
				}
			} else {
				const questionMediaInput = $(`#question-media-${questionId}`);
				const mediaFile = questionMediaInput[0]?.files[0];

				if (!mediaFile) {
					questionMediaInput.addClass('input-error');
					$(`#question-media-val-${questionId}`).removeClass('hidden');
					if (!firstInvalidElement) {
						firstInvalidElement = questionMediaInput[0];
						firstErrorIndex = index;
						errorType = 'question';
					}
					isValid = false;
				}
			}

			$(`#choices-${questionId} .choice-item`).each(function () {
				console.log('Validating choice');
				const $choiceItem = $(this);
				const $choiceValidatorInput = $choiceItem.find('.choice-validator-input');

				if (choicesType === 'text') {
					const $textInput = $choiceItem.find('input[type="text"]');
					const value = $textInput.val().trim(); // <-- THIS LINE WAS MISSING

					if (!value) {
						$textInput.addClass('input-error');
						$choiceValidatorInput.text('This choice cannot be empty.').removeClass('hidden');
						isValid = false;

						if (!firstInvalidElement) {
							firstInvalidElement = $textInput[0];
							firstErrorIndex = index;
							errorType = 'question';
						}
					} else {
						$textInput.removeClass('input-error');
						$choiceValidatorInput.addClass('hidden');
					}
				} else if (choicesType === 'media') {
					const fileInput = $choiceItem.find('input[type="file"]')[0];
					if (!fileInput || !fileInput.files[0]) {
						$(fileInput).addClass('input-error');
						$choiceValidatorInput.text('Choice media is required.').removeClass('hidden');
						isValid = false;

						if (!firstInvalidElement) {
							firstInvalidElement = fileInput;
							firstErrorIndex = index;
							errorType = 'question';
						}
					} else {
						$(fileInput).removeClass('input-error');
						$choiceValidatorInput.addClass('hidden');
					}
				}
			});

			// === 2. Validate Radio Choice ===
			const selectedChoice = $(`input[name="correct-${questionId}"]:checked`).val();
			if (!selectedChoice) {
				const errorEl = $(`#choice-radio-val-${questionId}`);
				errorEl.removeClass('hidden');

				if (!firstInvalidElement) {
					firstInvalidElement = errorEl[0];
					firstErrorIndex = index;
					errorType = 'question';
				}

				isValid = false;
			}
		});
	}

	return {
		isValid,
		firstInvalidElement,
		firstErrorIndex,
		errorType
	};
}