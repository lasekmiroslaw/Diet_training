var $collectionHolder;
var $addExersiseLink = $('<a href="#" class="add_exersise_link">Add exersise</a>');
var $newLinkLi = $('<span></span>').append($addExersiseLink);

$(document).ready(function(){
	$(".exercise").click(function(e){
		$id = $(e.currentTarget).attr('id');
		e.stopImmediatePropagation();
		$('.add_exersise_link').click();
		$('#myModal').modal();
		$('.hidenExerciseId').val($id);
		$('.exerciseName').html($(this).html());
	});

	$collectionHolder = $('ul.series');

	// $('.removeLink').on('click', function(e) {
	// 	e.preventDefault();
	// 	e.stopImmediatePropagation();
	// 	$(this).parent().remove();
	// });

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

}

// function addExersiseFormDeleteLink($exersiseFormLi) {
// 	var $removeFormA = $('<a href="#" class="removeLink">usuń</a>');
// 	$exersiseFormLi.append($removeFormA);
//
// 	$removeFormA.on('click', function(e) {
// 		e.preventDefault();
// 		e.stopImmediatePropagation();
// 		$exersiseFormLi.remove();
// 	});

	// $(".nextButton").click(function(e){
	// 	e.stopImmediatePropagation();
	// 	let num = +$('#user_training_form_series').val() + 1;
	// 	$('#user_training_form_series').val(num);
	// 	const url = $(location).attr('href');
	// 	return $.ajax({
	// 		url:  url,
	// 		type: "POST",
	// 		dataType: "json",
	// 		data:
	// 		  training_data,
	// 		success: function(data)
	// 		{
    //
    //
	// 		}
	// 	});
	// });
