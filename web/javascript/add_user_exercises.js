var $collectionHolder;
var $addExersiseLink = $('<a href="#" class="add_exersise_link">Add exersise</a>');
var $newLinkLi = $('<span></span>').append($addExersiseLink);
var $id = $('.exercise').attr('id');
var DisabledId = [];
var Id = {};
var $newFormLi = $(`<li class="series${$id}"></li>`);

$(document).ready(function(){
	$collectionHolder = $('ul.series');
	$collectionHolder.append($newLinkLi);
	$collectionHolder.data('index', $collectionHolder.find(':input').length);

	$(".exercise").click(function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		$id = $(e.currentTarget).attr('id');
		Id.id = $id;
		$('.hidenExerciseId').html($id);
		$(`#myModal`).modal();
		$('.exerciseName').html($(this).html());
		$(`.series${$id}`).removeClass('hide');
		DisabledId.forEach(function(id) {
			$(`.series${id}`).addClass('hide');
			$(`.series${$id}`).removeClass('hide');
		});
		addExersiseForm($collectionHolder, $newLinkLi, $id)
		console.log(Id.id)
	});

	// $('.removeLink').on('click', function(e) {
	// 	e.preventDefault();
	// 	e.stopImmediatePropagation();
	// 	$(this).parent().remove();
	// });

	$addExersiseLink.on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		addExersiseSpecificForm($newFormLi, Id.id)
	});
});
function addExersiseForm($collectionHolder, $newLinkLi, $id) {
	// console.log($collectionHolder);
	if(DisabledId.includes($id)) {
		return false;
	}
	var prototype = $collectionHolder.data('prototype');
	var newForm = prototype;

	newForm = newForm.replace(/__name__/g, $id,);
	var $newFormLi = $(`<li class="series${$id}"></li>`).append(newForm);


	$newLinkLi.append($newFormLi);
	DisabledId.push($id);
	var $innerPrototype = $(`#training_collection_form_trainingExercises_${$id}_seriesTraining`).data('prototype');
	var $newFormElement = $newFormLi.append($innerPrototype);
	$newLinkLi.after($newFormElement);
}

function addExersiseSpecificForm($newFormLi, $id) {

	var $innerCollectionHolder = $(`#training_collection_form_trainingExercises_${$id}_seriesTraining`);
	var $innerPrototype = $innerCollectionHolder.data('prototype');
	$innerCollectionHolder.data('index', $id);
	var index = $innerCollectionHolder.data('index');

	$innerPrototype = $innerPrototype.replace(/__name__/g, index);
	$innerCollectionHolder.data('index', parseInt($id) + 1);

	var $newFormElement = $newFormLi.append($innerPrototype);
	$newLinkLi.after($newFormElement);
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


function persistCollection($id) {
	product_data = {
		exerciseId: $id,
	};
	const url = $(location).attr('href');
	return $.ajax({
		url:  url,
		type: "POST",
		dataType: "json",
		data:
		  product_data,
		success: function(data)
		{

		},

	});
}
