var $collectionHolder;
var $addExersiseLink = $('<a href="#" class="add_exersise_link">Add exersise</a>');
var $newLinkLi = $('<span></span>').append($addExersiseLink);

$(document).ready(function() {
	$collectionHolder = $('ul.exersises');

	$('.removeLink').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).parent().remove();
	});

	$collectionHolder.append($newLinkLi);
	$collectionHolder.data('index', $collectionHolder.find(':input').length);

	$addExersiseLink.on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();

		addExersiseForm($collectionHolder, $newLinkLi);
	});
});

function addExersiseForm($collectionHolder, $newLinkLi) {
	var prototype = $collectionHolder.data('prototype');
	var index = $collectionHolder.data('index');
	var newForm = prototype;

	newForm = newForm.replace(/__name__/g, index);
	$collectionHolder.data('index', index + 1);
	var $newFormLi = $('<li></li>').append(newForm);
	$newLinkLi.before($newFormLi);

	addExersiseFormDeleteLink($newFormLi);
}

function addExersiseFormDeleteLink($exersiseFormLi) {
	var $removeFormA = $('<a href="#" class="removeLink">usuń</a>');
	$exersiseFormLi.append($removeFormA);

	$removeFormA.on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$exersiseFormLi.remove();
	});
}
