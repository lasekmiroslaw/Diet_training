var $collectionHolder;
var $addExersiseLink = $('<a href="#" id="addLink" >Dodaj serie</a>');
var $newLinkLi = $('<span></span>').append($addExersiseLink);
var $id = $('.exercise').attr('id');
var DisabledId = [];
var Id = {};
var $newFormLi = $(`<li class="series${$id}"></li>`);
var counter = 0;

$(document).ready(function(){
	$collectionHolder = $('ul.series');
	$collectionHolder.append($newLinkLi);
	$collectionHolder.data('index', $collectionHolder.find(':input').length);

	$(".exercise").click(function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		$id = $(e.currentTarget).attr('id');
		Id.id = $id;

		$(`#myModal`).modal();
		$('.exerciseName').html($(this).html());
		$(`.series${$id}`).removeClass('hide');

		DisabledId.forEach(function(id) {
			$(`.series${id}`).addClass('hide');
			$(`.series${$id}`).removeClass('hide');
		});

		addExersiseForm($collectionHolder, $newLinkLi, $id)
		$newFormLi = $(`<li class="series${$id}"></li>`);
	});

	$('.removeLink').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).parent().remove();
	});

	$addExersiseLink.on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		counter++;
		
		addExersiseSpecificForm($newFormLi, $id);
	});
});

function addExersiseForm($collectionHolder, $newLinkLi, $id) {
	if(DisabledId.includes($id)) {
		return false;
	}
	var prototype = $collectionHolder.data('prototype');
	var newForm = prototype;

	var $newFormLi = $(`<li class="series${$id}"></li>`).append(newForm);

	$newLinkLi.append($newFormLi);
	DisabledId.push($id);

	var $innerCollectionHolder = $(`#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', counter);
	var index = $innerCollectionHolder.data('index');

	$innerPrototype = $innerPrototype.replace(/__name__/g, index);
	$innerCollectionHolder.data('index');

	var $newFormElement = $newFormLi.append($innerPrototype);
	$newLinkLi.after($newFormElement);

}

function addExersiseSpecificForm($newFormLi, $id) {
	$id = Id.id;
	var $innerCollectionHolder = $(`#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', counter);
	var index = $innerCollectionHolder.data('index');

	$innerCollectionHolder.data('index', parseInt(counter) + 1);
	$innerPrototype = $innerPrototype.replace(/__name__/g, counter);


	var $newFormElement = $newFormLi.append($innerPrototype);
	$newLinkLi.after($newFormElement);
	addExersiseFormDeleteLink($newFormElement);
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
