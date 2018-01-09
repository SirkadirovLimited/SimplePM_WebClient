$('#tags')[0].addEventListener('input', log);
$('#tags')[0].addEventListener('change', log);
function log(e) {
    $('#out')[0].textContent = `${e.type}: ${this.value.replace(/,/g,', ')}`;
}

// hook 'em up:
$('input[type="tags"]').forEach(tagsInput);
