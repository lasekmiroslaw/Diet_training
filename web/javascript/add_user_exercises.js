var $collectionHolder;
var $newLinkLi = $('<div></div>');
var DisabledId = [];
var Id = {};
var counter = 1;
var Indexes = [];
var $addSeries = $(`<button type="button" class="addSeries btn btn-success">Dodaj serie</button>`);
var $previousSeries = $('<button type="button" class="previousBtn btn btn-info">Poprzednia seria</button>');
var $nextSeries = $('<button type="button" class="btn btn-primary nextSeries">Następna seria</button>');
var $removeSeries = $('<button type="button" class="btn btn-danger removeSeries">Usuń</button>');
var $saveSeries = $('<button type="button" class="btn btn-success saveSeries">Zapisz</button>');

$(document).ready(function() {
	$collectionHolder = $('ul.series');
	$collectionHolder.append($newLinkLi);
	$collectionHolder.data('index', $collectionHolder.find(':input').length);


	$(".exercise").click(function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$id = $(e.currentTarget).attr('id');
		Id.id = $id;
		$newFormLi = $(`<li class="exercise${$id}"></li>`);
		//Set counter to current number of series
		var series = Indexes[$id];
		if (Number.isInteger(series)) {
			counter = series;
		} else {
			counter = parseInt(1);
		}

		$(`#myModal`).modal();
		$('.exerciseName').html($(this).html());
		//Make visible current exercise
		$(`.exercise${$id}`).removeClass('hide');

		//Make visible only current series and hide rest
		DisabledId.forEach(function(id) {
			$(`.exercise${id}`).addClass('hide');
			$(`.exercise${$id}`).removeClass('hide');
		});

		//Function works only if user click link first time
		if (!(DisabledId.includes($id))) {
			addExersiseForm($collectionHolder, $newLinkLi, $id);
		}
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

		var $buttonToChange = $(`button.previous${$id}${counter-1}`).next('button.addSeries');
		var $buttonToHide = $(`button.previous${$id}${counter-1}`).siblings('button.removeSeries');
		$buttonToChange.replaceWith($($nextSeries).clone(true));
		$buttonToHide.addClass('hide');

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
	$($removeSeries).on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();

		$(this).parent().parent().remove();
		$(`#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter-1}`).removeClass('hide');
		var $buttonToChange = $(`button.previous${$id}${counter-1}`).next('button.nextSeries');
		var $removeButton = $(`button.previous${$id}${counter-1}`).siblings('button.removeSeries');

		if(counter == 2) {
			var $firstbuttonToChange = $(`input#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter-1}_series`).parent().next('button.nextSeries');
			$firstbuttonToChange.replaceWith($($addSeries).clone(true));
		}
		$buttonToChange.replaceWith($($addSeries).clone(true));
		$removeButton.removeClass('hide')
		--counter;
		Indexes[$id] = counter;
	});
	$('button.saveSeries').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		var $exerciseLoadReps = $(`div.exerciseDiv${$id}`).children('table.kgLoadReps').find('tbody');
		var $kgRepsHolder = $(`li.exercise${$id}`).find(`input.kgLoad, input.reps`);
		$exerciseLoadReps.html('');
		var i = 1;
		$.each($kgRepsHolder, function(key, value) {

			if(key % 2 == 0) {
				$exerciseLoadReps = $exerciseLoadReps.append('<tr class="seriesData"></tr>');
				$exerciseLoadReps.append(`<td>seria ${i}</td><td>${$(value).val()}kg</td>`);
				i++;
			} else {
				$exerciseLoadReps.append(`<td>x</td><td>${$(value).val()}</td>`);
			}
			$('#myModal').modal('hide');
		});
	});
});

function addExersiseForm($collectionHolder, $newLinkLi, $id) {
	var prototype = $collectionHolder.data('prototype');
	var newForm = prototype;
	$newFormLi.append(newForm);

	$newLinkLi.append($newFormLi);
	DisabledId.push($id);

	var $innerCollectionHolder = $(`div#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', 1);
	var index = $innerCollectionHolder.data('index');

	$innerPrototype = $innerPrototype.replace(/__name__/g, index);
	$innerCollectionHolder.data('index');

	var $newFormElement = $newFormLi.append($innerPrototype);
	// $newFormLi.append($saveSeries);
	$($newFormElement).find(`input#training_collection_form_trainingExercises_${$id}_seriesTraining_1_series`).attr('value', 1);
	$newLinkLi.append($newFormElement);
	$($addSeries).clone(true).appendTo(`div#training_collection_form_trainingExercises_${$id}_seriesTraining_1`);
}

function addExersiseSpecificForm($newFormLi) {
	$id = Id.id;
	var $innerCollectionHolder = $(`div#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', counter);
	var index = $innerCollectionHolder.data('index');
	Indexes[$id] = index;
	$innerPrototype = $innerPrototype.replace(/__name__/g, counter);

	var $newFormElement = $newFormLi.append($innerPrototype);
	$($newFormElement).find(`input#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}_series`).attr('value', counter);
	$newLinkLi.append($newFormElement);
	$(`div#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter-1}`).addClass('hide');
	addButtons();
}

function addButtons() {
	$($previousSeries).clone(true).appendTo(`div#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`);
	$($addSeries).clone(true).appendTo(`div#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`);
	$($removeSeries).clone(true).appendTo(`div#training_collection_form_trainingExercises_${$id}_seriesTraining_${counter}`);
}
