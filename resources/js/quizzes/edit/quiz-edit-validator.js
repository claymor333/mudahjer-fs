/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.validateStep = validateStep;

function validateStep(step) {
	let isValid = true;
	console.log(`🚀 Validating Step ${step}`);

	if (step === 1) {
		console.log('📋 Step 1: Quiz basic info');

		const title = $('#quiz-title').val().trim();
		if (!title) {
			console.warn('❌ Title is empty');
			$('#quiz-title').addClass('input-error');
			$('#quiz-title-val').removeClass('hidden');
			isValid = false;
		} else {
			console.log('✅ Title OK');
			$('#quiz-title').removeClass('input-error');
			$('#quiz-title-val').addClass('hidden');
		}

		const description = $('#quiz-description').val().trim();
		if (!description) {
			console.warn('❌ Description is empty');
			$('#quiz-description').addClass('input-error');
			$('#quiz-description-val').removeClass('hidden');
			isValid = false;
		} else {
			console.log('✅ Description OK');
			$('#quiz-description').removeClass('input-error');
			$('#quiz-description-val').addClass('hidden');
		}

		const lessonVal = $('#quiz-lesson').val();
		if (!lessonVal) {
			console.warn('❌ Lesson not selected');
			$('#quiz-lesson').addClass('input-error');
			$('#quiz-lesson-val').removeClass('hidden');
			isValid = false;
		} else {
			console.log('✅ Lesson OK');
			$('#quiz-lesson').removeClass('input-error');
			$('#quiz-lesson-val').addClass('hidden');
		}

	} else if (step === 2) {
		console.log('📋 Step 2: Notes');
		notes.forEach((noteId) => {
			const noteText = $(`#note-text-${noteId}`).val().trim();
			const noteMediaInput = $(`#note-media-${noteId}`)[0].files[0];
			const hasMediaPreview = $(`#media-preview-${noteId}`).find('img, video').length > 0;

			if (!noteText) {
				console.warn(`❌ Note ${noteId} text is empty`);
				$(`#note-text-${noteId}`).addClass('input-error');
				$(`#note-text-val-${noteId}`).removeClass('hidden');
				isValid = false;
			} else {
				console.log(`✅ Note ${noteId} text OK`);
				$(`#note-text-${noteId}`).removeClass('input-error');
				$(`#note-text-val-${noteId}`).addClass('hidden');
			}

			if (!noteMediaInput && !hasMediaPreview) {
				console.warn(`❌ Note ${noteId} media missing`);
				$(`#note-media-${noteId}`).addClass('input-error');
				$(`#note-media-val-${noteId}`).removeClass('hidden');
				isValid = false;
			} else {
				console.log(`✅ Note ${noteId} media OK`);
				$(`#note-media-${noteId}`).removeClass('input-error');
				$(`#note-media-val-${noteId}`).addClass('hidden');
			}
		});

	} else if (step === 3) {
		console.log('📋 Step 3: Questions');

		questions.forEach((questionId, index) => {
			const questionText = $(`#question-text-${questionId}`).val().trim();
			const choicesType = $('#quiz-choices-type').val();
			const $questionRadioValidator = $(`#choice-radio-val-${questionId}`);

			let hasCorrectAnswer = $(`input[name="questions[${index}][correct_choice]"]:checked`).length > 0;
			let hasEmptyChoice = false;

			if (!questionText) {
				console.warn(`❌ Question ${index + 1} text is empty`);
				$(`#question-text-${questionId}`).addClass('input-error');
				$(`#question-text-val-${questionId}`).removeClass('hidden');
				isValid = false;
			} else {
				console.log(`✅ Question ${index + 1} text OK`);
				$(`#question-text-${questionId}`).removeClass('input-error');
				$(`#question-text-val-${questionId}`).addClass('hidden');
			}

			$(`#choices-${questionId} .choice-item`).each(function () {
				const $choiceItem = $(this);
				const $choiceValidatorInput = $choiceItem.find('.choice-validator-input');
				const $radioInput = $choiceItem.find('input[type="radio"]');

				if (choicesType === 'media') {
					const fileInput = $choiceItem.find('input[type="file"]')[0];
					const hasExistingMedia = $choiceItem.find('img, video').length > 0;

					if (!fileInput?.files[0] && !hasExistingMedia) {
						console.warn(`❌ Question ${index + 1} choice media missing`);
						$choiceValidatorInput.text('Please select a file or keep the existing media.').removeClass('hidden');
						$(fileInput).addClass('input-error');
						hasEmptyChoice = true;
					} else {
						console.log(`✅ Question ${index + 1} choice media OK`);
						$choiceValidatorInput.addClass('hidden');
						$(fileInput).removeClass('input-error');
					}
				} else {
					const textInput = $choiceItem.find('input[type="text"]');
					if (!textInput.val().trim()) {
						console.warn(`❌ Question ${index + 1} choice text is empty`);
						textInput.addClass('input-error');
						$choiceValidatorInput.text('This choice cannot be empty.').removeClass('hidden');
						hasEmptyChoice = true;
					} else {
						console.log(`✅ Question ${index + 1} choice text OK`);
						textInput.removeClass('input-error');
						$choiceValidatorInput.addClass('hidden');
					}
				}

				if (!$radioInput.is(':checked')) {
					$radioInput.addClass('input-error').removeClass('radio-primary');
				} else {
					$radioInput.removeClass('input-error').addClass('radio-primary');
				}
			});

			if (!hasCorrectAnswer) {
				console.warn(`❌ Question ${index + 1} has no correct answer selected`);
				$questionRadioValidator.removeClass('hidden');
				isValid = false;
			} else {
				console.log(`✅ Question ${index + 1} correct answer OK`);
				$questionRadioValidator.addClass('hidden');
			}

			if (hasEmptyChoice) {
				isValid = false;
			}
		});
	}

	console.log(`🎯 Step ${step} validation result: ${isValid ? '✅ Valid' : '❌ Invalid'}`);
	return isValid;
}