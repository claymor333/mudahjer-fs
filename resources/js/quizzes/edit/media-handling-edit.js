/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.renderMediaPreview = renderMediaPreview;
window.handleMediaPreview = handleMediaPreview;
window.previewMedia = previewMedia;
window.showPreviewModal = showPreviewModal;
window.closePreviewModal = closePreviewModal;
window.removeMedia = removeMedia;

/**
* Render media preview HTML
* @param {string} path
* @param {string} questionId
* @returns {string}
*/
function renderMediaPreview(path, questionId) {
	const isImage = path.match(/\.(jpg|jpeg|png|gif|webp)$/i);
	const src = `/storage/${path}`;
	return `
		<div class="media-preview relative">
			${isImage
			? `<img src="${src}" data-media-src="${src}" data-media-type="image" class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">`
			: `<video controls class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
					<source src="${src}" type="video/mp4">
				</video>`}
			<button type="button" class="btn btn-sm btn-circle btn-error absolute top-2 right-2" onclick="removeMedia('${questionId}')">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
			<input type="hidden" name="questions[][existing_media]" value="${path}">
		</div>`;
}

$(document).on('change', '.choice-media-input', function () {
	const fileInput = this;
	const file = fileInput.files[0];
	const $container = $(fileInput).closest('.indicator');
	const $imgBadge = $container.find('.choice-preview-img');
	const $videoBadge = $container.find('.choice-preview-video');
	const $img = $imgBadge.find('img');
	const $svg = $videoBadge.find('svg');

	// Reset both badges
	$imgBadge.addClass('hidden');
	$videoBadge.addClass('hidden');
	$img.attr('src', '');

	if (file) {
		const reader = new FileReader();

		if (file.type.startsWith('image/')) {
			reader.onload = function (e) {
				$img.attr('src', e.target.result);
				$img.off('click').on('click', () => showPreviewModal($img[0]));
				$imgBadge.removeClass('hidden');
			};
			reader.readAsDataURL(file);
		} else if (file.type.startsWith('video/')) {
			reader.onload = function (e) {
				$svg.off('click').on('click', () => {
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
});


function handleMediaPreview(input) {
	const file = input.files[0];
	if (!file) return;

	const container = $(input).closest('.indicator');
	const imageBadge = container.find('.choice-preview-img');
	const videoBadge = container.find('.choice-preview-video');

	const url = URL.createObjectURL(file);

	const isImage = file.type.startsWith('image/');
	const isVideo = file.type.startsWith('video/');

	if (isImage) {
		imageBadge.find('img')
			.attr('src', url)
			.attr('data-media-src', url)
			.attr('data-media-type', 'image')
			.removeClass('hidden');
		videoBadge.addClass('hidden');
	} else if (isVideo) {
		videoBadge
			.attr('data-media-src', url)
			.attr('data-media-type', 'video')
			.removeClass('hidden');
		imageBadge.addClass('hidden');
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
		preview.append(mediaContainer);
	}
}

function showPreviewModal(element) {
	const modal = $('#preview-modal');
	const content = $('#preview-content');
	content.empty();

	console.log(element)

	// Try to get data attributes from element or parent
	const mediaType = element.dataset.mediaType || element.closest('[data-media-type]')?.dataset.mediaType;
	const mediaSrc = element.dataset.mediaSrc || element.closest('[data-media-src]')?.dataset.mediaSrc;

	console.log('mediaSrc:', mediaSrc);
	console.log('mediaType:', mediaType);

	if (!mediaType || !mediaSrc) return;

	if (mediaType === 'video') {
		const video = $('<video controls autoplay class="w-full max-h-[80vh] object-contain">')
			.attr('src', mediaSrc);
		content.append(video);
	} else if (mediaType === 'image') {
		const img = $('<img class="w-full max-h-[80vh] object-contain">')
			.attr('src', mediaSrc);
		content.append(img);
	}

	modal.addClass('active');

	// Close on Escape
	$(document).off('keydown.preview').on('keydown.preview', function (e) {
		if (e.key === 'Escape') {
			closePreviewModal();
		}
	});

	// Close on click outside
	modal.off('click.preview').on('click.preview', function (e) {
		if ($(e.target).closest('.content').length === 0) {
			closePreviewModal();
		}
	});
}

function closePreviewModal() {
	const modal = $('#preview-modal');
	modal.removeClass('active');

	// Clean up event handlers
	$(document).off('keydown.preview');
	modal.off('click.preview');
}

function removeMedia(questionId) {
	const questionCard = $(`#${questionId}`);
	const preview = $(`#media-preview-${questionId}`);
	const fileInput = questionCard.find('input[type="file"]');

	preview.empty();
	fileInput.val('');
	questionCard.removeAttr('data-has-media');
	questionCard.attr('data-remove-media', 'true'); // mark it for saveQuiz
}