/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.handleChoiceMediaPreview = handleChoiceMediaPreview;
window.previewMedia = previewMedia;
window.removeMedia = removeMedia;
window.closePreviewModal = closePreviewModal;
window.showPreviewModal = showPreviewModal;

function handleChoiceMediaPreview(input) {
	const $input = $(input);
	const file = input.files[0];
	if (!file) return;

	const $imgBadge = $input.siblings('.choice-preview-img');
	const $videoBadge = $input.siblings('.choice-preview-video');
	const previewImg = $imgBadge.find('img')[0];
	const videoIcon = $videoBadge.find('svg')[0];

	// Reset both badges
	$imgBadge.addClass('hidden');
	$videoBadge.addClass('hidden');

	const reader = new FileReader();

	if (file.type.startsWith('image/')) {
		reader.onload = function (e) {
			$(previewImg)
				.attr('src', e.target.result)
				.off('click')
				.on('click', function () {
					showPreviewModal(previewImg);
				});
			$imgBadge.removeClass('hidden');
		};
		reader.readAsDataURL(file);

	} else if (file.type.startsWith('video/')) {
		reader.onload = function (e) {
			$(videoIcon)
				.off('click')
				.on('click', function () {
					const video = document.createElement('video');
					video.src = e.target.result;
					video.controls = true;
					showPreviewModal(video);
				});
			$videoBadge.removeClass('hidden');
		};
		reader.readAsDataURL(file);
	}
}

function previewMedia(input, elementId) {
	const preview = $(`#media-preview-${elementId}`);
	preview.empty();

	if (input.files && input.files[0]) {
		const file = input.files[0];
		const isVideo = file.type.startsWith('video/');
		const url = URL.createObjectURL(file);

		const mediaContainer = $('<div class="media-preview relative"></div>');

		if (isVideo) {
			const video = $(`<video controls class="w-full max-w-md h-64 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
                        <source src="${url}" type="${file.type}">
                    </video>`);
			mediaContainer.append(video);
		} else {
			const img = $(`<img src="${url}" class="w-full max-w-md max-h-[70vh] object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">`);
			mediaContainer.append(img);
		}

		const removeBtn = $(`
                    <button type="button" class="btn btn-sm btn-circle btn-error absolute top-2 right-2" onclick="removeMedia('${elementId}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `);

		mediaContainer.append(removeBtn);
		preview.removeClass("hidden");
		preview.append(mediaContainer);
	}
}

function removeMedia(questionId) {
	const preview = $(`#media-preview-${questionId}`);
	const fileInput = $(`#${questionId}`).find('input[type="file"]');

	preview.empty();
	fileInput.val('');
}

function closePreviewModal() {
	const modal = $('#preview-modal');
	modal.removeClass('active');

	// Clean up event handlers
	$(document).off('keydown.preview');
	modal.off('click.preview');
}

function showPreviewModal(element) {
	const modal = $('#preview-modal');
	const content = $('#preview-content');
	content.empty();

	if (element.tagName.toLowerCase() === 'video') {
		// Clone video with all its sources
		const video = $(element).clone();
		video.removeClass('object-cover').addClass('object-contain');
		video[0].currentTime = element.currentTime; // Sync video position
		content.append(video);
	} else {
		// Clone image
		const img = $(element).clone();
		img.removeClass('object-cover').addClass('object-contain');
		content.append(img);
	}

	modal.addClass('active');

	// Close on escape key
	$(document).on('keydown.preview', function (e) {
		if (e.key === 'Escape') {
			closePreviewModal();
		}
	});

	// Close on click outside content
	modal.on('click.preview', function (e) {
		if ($(e.target).closest('.content').length === 0) {
			closePreviewModal();
		}
	});
}