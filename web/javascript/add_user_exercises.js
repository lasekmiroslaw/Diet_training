var $collectionHolder;
var $newLinkLi = $('<span></span>');
var DisabledId = [];
var Id = {};
var counter = 1;
var Indexes = [];
var $addSeries = $(`<button type="button" class="addSeries">Dodaj serie</button>`);
var $previousSeries = $('<button type="button" class="previousBtn">Poprzednia seria</button>');
var $nextSeries = $('<button class="button nextSeries" type="button">Następna seria</button>');

$(document).ready(function() {
	$collectionHolder = $('ul.series');
	$collectionHolder.append($newLinkLi);
	$collectionHolder.data('index', $collectionHolder.find(':input').length);

	$(".exercise").click(function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$id = $(e.currentTarget).attr('id');
		Id.id = $id;
		$newFormLi = $(`<li class="series${$id}"></li>`);
		//Counting how many series has been added
		console.log(Indexes[$id])
		var series = Indexes[$id];
		if (Number.isInteger(series)) {
			counter = series;
		} else {
			counter = parseInt(1);
		}

		$(`#myModal`).modal();
		$('.exerciseName').html($(this).html());
		//Make visible current exercise
		$(`.series${$id}`).removeClass('hide');

		//Make visible only current series and hide rest
		DisabledId.forEach(function(id) {
			$(`.series${id}`).addClass('hide');
			$(`.series${$id}`).removeClass('hide');
		});

		//Function works only if user click link first time
		if(!(DisabledId.includes($id))) {
			addExersiseForm($collectionHolder, $newLinkLi, $id);
		}
	});

	$('.removeLink').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).parent().remove();
	});

	$($addSeries).on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		++counter;
		addExersiseSpecificForm($newFormLi);
		$(this).prev('.previousBtn').addClass(`previous${$id}${counter-1}`);
		$(this).replaceWith($($nextSeries).clone(true));
	});
	$($previousSeries).on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter-1}`).removeClass('hide');
		$(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`).addClass('hide');
		var $buttonToChange = $(`button.previous${$id}${counter-1}`).next('.addSeries');

		$buttonToChange.replaceWith($($nextSeries).clone(true));
		--counter;
		Indexes[$id] = counter;
	});
	$($nextSeries).on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();

		$(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter+1}`).removeClass('hide');
		$(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`).addClass('hide');
		++counter;
		Indexes[$id] = counter;
	});
});

function addExersiseForm($collectionHolder, $newLinkLi, $id) {
	var prototype = $collectionHolder.data('prototype');
	var newForm = prototype;
	var $newFormLi = $(`<li class="series${$id}"></li>`).append(newForm);

	$newLinkLi.append($newFormLi);
	DisabledId.push($id);

	var $innerCollectionHolder = $(`#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', 1);
	var index = $innerCollectionHolder.data('index');

	$innerPrototype = $innerPrototype.replace(/__name__/g, index);

	$innerCollectionHolder.data('index');

	var $newFormElement = $newFormLi.append($innerPrototype);
	$($newFormElement).find(`input#training_collection_form_trainingExercises_${$id}_seriesTraining_1_series`).attr('value', 1);
	$($addSeries).clone(true).appendTo(`#training_collection_form_trainingExercises_${$id}_seriesTraining_1`);
	$newLinkLi.append($newFormElement);
}

function addExersiseSpecificForm($newFormLi) {
	$id = Id.id;
	var $innerCollectionHolder = $(`#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', counter);
	var index = $innerCollectionHolder.data('index');

	Indexes[$id] = index;
	$innerPrototype = $innerPrototype.replace(/__name__/g, counter);

	var $newFormElement = $newFormLi.append($innerPrototype);
	$($newFormElement).find(`input#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}_series`).attr('value', counter);
	$newLinkLi.append($newFormElement);
	$(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter-1}`).addClass('hide');
	$($previousSeries).clone(true).appendTo(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`);
	$($addSeries).clone(true).appendTo(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`);
}

function showPreviousSeries() {

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
