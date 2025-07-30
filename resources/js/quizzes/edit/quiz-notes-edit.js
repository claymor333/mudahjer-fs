/// regex: ^function\s+([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(
/// replace key: 	window.$1 = $1;
// 					function $1(

/// function funcName (params) { to
//  window.funcName = funcName 
// 	function funcName (params) {

window.addNote = addNote;
window.navigateToNote = navigateToNote;
window.prevNote = prevNote;
window.nextNote = nextNote;
window.updateNoteCounter = updateNoteCounter;
window.removeNote = removeNote;

function addNote(existingId = null, noteText = '', mediaPath = null) {
	const nextNumber = notes.length + 1;
	const noteId = existingId ? `note-${existingId}` : `note-${Date.now()}`;

	const noteHtml = `
                <div class="note-card card bg-base-200 shadow-lg" id="${noteId}" data-sequence="${nextNumber}" ${existingId ? `data-existing-id="${existingId}"` : ''}>
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Note ${nextNumber}</h3>
                            <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="removeNote('${noteId}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <input type="hidden" name="notes[${nextNumber - 1}][id]" value="${existingId || ''}">

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Note Text</span>
                            </legend>
                            <input type="text" id="note-text-${noteId}" name="notes[${nextNumber - 1}][note_text]" class="w-full input input-bordered validator" placeholder="Enter your note..." value="${noteText}" required>
                            <p id="note-text-val-${noteId}" class="text-error hidden">Note text is required.</p>

                            <legend class="fieldset-legend">
                                <span class="font-medium text-lg">Media</span>
                            </legend>
                            <input type="file" id="note-media-${noteId}" name="notes[${nextNumber - 1}][media]" class="w-full file-input file-input-bordered validator" accept="image/*,video/*" onchange="previewMedia(this, '${noteId}')" required>
                            <p id="note-media-val-${noteId}" class="text-error hidden">Media file is required.</p>
                            <div class="media-preview mt-4" id="media-preview-${noteId}">
                                ${mediaPath ? `
                                    <div class="media-preview relative">
                                        ${mediaPath.match(/\.(jpg|jpeg|png|gif|webp)$/i)
				? `<img src="/storage/${mediaPath}" data-media-src="/storage/${mediaPath}" data-media-type="image" class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">`
				: `<video controls class="w-full max-w-md h-48 object-cover rounded-lg mx-auto block" onclick="showPreviewModal(this)">
                                                <source src="/storage/${mediaPath}" type="video/mp4">
                                            </video>`
			}
                                        <button type="button" class="btn btn-sm btn-circle btn-error absolute top-2 right-2" onclick="removeMedia('${noteId}')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <input type="hidden" name="notes[${nextNumber - 1}][existing_media]" value="${mediaPath}">
                                    </div>
                                ` : ''}
                            </div>
                        </fieldset>
                    </div>
                </div>
            `;

	$('#notes-carousel').append(noteHtml);
	notes.push(noteId);

	navigateToNote(notes.length - 1);
	updateNoteCounter();
	updateNavigationButtons();
}

function navigateToNote(index) {
	if (index < 0 || index >= notes.length) return;

	currentNoteIndex = index;

	notes.forEach((noteId, i) => {
		const noteCard = $(`#${noteId}`);
		const diff = i - index;

		noteCard.removeClass('prev-2 prev-1 active next-1 next-2');

		if (diff === -2) noteCard.addClass('prev-2');
		else if (diff === -1) noteCard.addClass('prev-1');
		else if (diff === 0) noteCard.addClass('active');
		else if (diff === 1) noteCard.addClass('next-1');
		else if (diff === 2) noteCard.addClass('next-2');
	});
}

function prevNote() {
	if (currentNoteIndex > 0) {
		navigateToNote(currentNoteIndex - 1);
		updateNoteCounter();
		updateNavigationButtons();
	}
}

function nextNote() {
	if (currentNoteIndex < notes.length - 1) {
		// normal: go to next note
		navigateToNote(currentNoteIndex + 1);
	} else {
		// already at the last note → create a new one
		addNote();
	}
	updateNoteCounter();
	updateNavigationButtons();
}

function updateNoteCounter() {
	$('#current-note-num').text(currentNoteIndex + 1);
	$('#total-notes').text(notes.length);
}

function removeNote(noteId) {
	const noteIndex = notes.indexOf(noteId);
	if (noteIndex === -1) return;

	if (notes.length <= 1) {
		alert('You must have at least one note!');
		return;
	}

	$(`#${noteId}`).remove();
	notes.splice(noteIndex, 1);

	// Update sequence numbers for all notes
	notes.forEach((nId, index) => {
		const noteCard = $(`#${nId}`);
		const newSequence = index + 1;
		noteCard
			.attr('data-sequence', newSequence)
			.find('h3')
			.text(`Note ${newSequence}`);
	});

	if (currentNoteIndex >= notes.length) {
		currentNoteIndex = notes.length - 1;
	}

	navigateToNote(currentNoteIndex);
	updateNoteCounter();
	updateNavigationButtons();
}