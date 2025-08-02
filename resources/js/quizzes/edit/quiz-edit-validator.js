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
			const hasPreview = $(`#media-preview-${noteId}`).find('img, video').length > 0;

			if (!noteText) {
				noteTextInput.addClass('input-error');
				$(`#note-text-val-${noteId}`).removeClass('hidden');
				if (!firstInvalidElement) {
					firstInvalidElement = noteTextInput[0];
					firstErrorIndex = index;
					errorType = 'note';
				}
				isValid = false;
			} else {
				noteTextInput.removeClass('input-error');
				$(`#note-text-val-${noteId}`).addClass('hidden');
			}

			if (!mediaFile && !hasPreview) {
				noteMediaInput.addClass('input-error');
				$(`#note-media-val-${noteId}`).removeClass('hidden');
				if (!firstInvalidElement) {
					firstInvalidElement = noteMediaInput[0];
					firstErrorIndex = index;
					errorType = 'note';
				}
				isValid = false;
			} else {
				noteMediaInput.removeClass('input-error');
				$(`#note-media-val-${noteId}`).addClass('hidden');
			}
		});

	} else if (step === 3) {
		questions.forEach((questionId, index) => {
			
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
				} else {
					questionInput.removeClass('input-error');
					$(`#question-text-val-${questionId}`).addClass('hidden');
				}
			} else {
				const questionMediaInput = $(`#question-media-${questionId}`);
				const hasMediaPreview = $(`#media-preview-${questionId}`).find('img, video').length > 0;
				const mediaFile = questionMediaInput[0]?.files[0];

				if (!mediaFile && !hasMediaPreview) {
					questionMediaInput.addClass('input-error');
					$(`#question-media-val-${questionId}`).removeClass('hidden');
					if (!firstInvalidElement) {
						firstInvalidElement = questionMediaInput[0];
						firstErrorIndex = index;
						errorType = 'question';
					}
					isValid = false;
				} else {
					questionMediaInput.removeClass('input-error');
					$(`#question-media-val-${questionId}`).addClass('hidden');
				}
			}

			$(`#choices-${questionId} .choice-item`).each(function () {
				const $choiceItem = $(this);
				const $choiceValidatorInput = $choiceItem.find('.choice-validator-input');

				if (choicesType === 'text') {
					const $textInput = $choiceItem.find('input[type="text"]');
					const value = $textInput.val().trim();
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
					const $imgBadge = $choiceItem.find('.choice-preview-img:not(.hidden) img[data-media-src]');
					const $videoBadge = $choiceItem.find('.choice-preview-video:not(.hidden)[data-media-src]');

					const hasUploadedFile = !!fileInput?.files?.[0];
					const hasExistingImage = $imgBadge.length > 0 && $imgBadge.attr('data-media-src')?.trim();
					const hasExistingVideo = $videoBadge.length > 0 && $videoBadge.attr('data-media-src')?.trim();

					const hasMedia = hasUploadedFile || hasExistingImage || hasExistingVideo;

					if (!hasMedia) {
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

			const selectedChoice = $(`#choices-${questionId} input[type="radio"]:checked`).val();
			if (!selectedChoice) {
				const errorEl = $(`#choice-radio-val-${questionId}`);
				errorEl.removeClass('hidden');
				if (!firstInvalidElement) {
					firstInvalidElement = errorEl[0];
					firstErrorIndex = index;
					errorType = 'question';
				}
				isValid = false;
			} else {
				$(`#choice-radio-val-${questionId}`).addClass('hidden');
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
