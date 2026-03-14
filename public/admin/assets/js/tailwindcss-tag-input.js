
    function createTagInput(selector, initialTags = []) {
    const $input = $(selector);
    if ($input.length === 0) return null;

    // Create DOM structure
    const $wrapper = $('<div class="tag-input-wrapper border custom-border rounded p-2 d-flex flex-wrap align-items-center gap-2"></div>');
    const $hiddenInput = $('<input type="hidden">');
    const $textInput = $('<input type="text" class=" border-0 flex-grow-1 p-0 shadow-none" style="outline: 0" autocomplete="off" placeholder="Add tag...">');

    // Copy original attributes
    const inputName = $input.attr('name') || '';
    const placeholder = $input.attr('placeholder') || 'Add tag...';
    $hiddenInput.attr('name', inputName);
    $textInput.attr('placeholder', placeholder);

    // Replace original input
    $input.after($wrapper);
    $wrapper.append($textInput);
    $wrapper.after($hiddenInput);
    $input.remove();

    let tags = [];

    // --- Utility functions ---
    const escapeHtml = (text) => $('<div>').text(text).html();

    const renderTags = () => {
    $wrapper.find('.badge').remove();
    tags.forEach((tag, index) => {
    const $tag = $(`
                <span class="badge bg-primary d-inline-flex align-items-center px-2 py-1">
                    <span class="me-1" style="font-size: 12px">${escapeHtml(tag)}</span>
                    <button type="button" class="btn-close btn-close-white ms-1" aria-label="Remove"></button>
                </span>
            `);
    $tag.find('button').on('click', () => {
    tags.splice(index, 1);
    renderTags();
});
    $textInput.before($tag);
});
    $hiddenInput.val(tags.join(','));
};

    const addTag = (tagText) => {
    tagText = tagText.trim();
    if (!tagText || tags.includes(tagText)) return;
    tags.push(tagText);
    renderTags();
};

    // --- Event handlers ---
    $textInput.on('keydown', (e) => {
    const value = $textInput.val().trim();
    if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    if (value) {
    addTag(value);
    $textInput.val('');
}
} else if (e.key === 'Backspace' && !value && tags.length) {
    tags.pop();
    renderTags();
}
});

    $textInput.on('blur', () => {
    const value = $textInput.val().trim();
    if (value) {
    addTag(value);
    $textInput.val('');
}
});

    // --- Initialize with given tags ---
    if (Array.isArray(initialTags)) {
    initialTags.forEach(addTag);
} else if (typeof initialTags === 'string' && initialTags.length > 0) {
    initialTags.split(',').forEach(tag => addTag(tag.trim()));
}

    // --- Expose API ---
    return {
    getValues: () => tags,
    setValues: (newTags) => {
    if (Array.isArray(newTags)) tags = newTags.map(t => t.trim());
    else if (typeof newTags === 'string') tags = newTags.split(',').map(t => t.trim());
    renderTags();
},
    clear: () => {
    tags = [];
    renderTags();
}
};
}
