var Training = {};
Training.activeAjaxConnections = 0;

$('.trainingList').on('click', 'li', getCaloriesOnClick);
$('#user_cardio_form_time_hour').change(getCaloriesOnChange);
$('#user_cardio_form_time_minute').change(getCaloriesOnChange);
$('button').click(checkData);

function getCaloriesOnClick(e) {
	e.stopImmediatePropagation();
	$('#user_cardio_form_time_minute').val(30);
	$('#user_cardio_form_time_hour').val(0);
	$training_id = $(e.currentTarget).attr('id');
	Training.$training_id = $training_id;
	$training_time = eval(($('#user_cardio_form_time_hour').val())*60) + eval($('#user_cardio_form_time_minute').val());
	trainig_data = {
		trainingId: $training_id,
		trainingTime: $training_time
	};
	addCalories();

	$(document).ajaxStop(function(){
    	$('.trainingForm').removeClass('hide');
	});
	return false;
}

function getCaloriesOnChange(e) {
	e.stopImmediatePropagation();
	$training_id = Training.$training_id;
	$training_time = eval(($('#user_cardio_form_time_hour').val())*60) + eval($('#user_cardio_form_time_minute').val());
	trainig_data = {
		trainingId: $training_id,
		trainingTime: $training_time
	};
	addCalories();
	return false;
}

function addCalories() {
	const url = $(location).attr('href');
	return $.ajax({
		beforeSend: function(xhr) {
			Training.activeAjaxConnections++;
			},
		url:  url,
		type: "POST",
		dataType: "json",
		data:
		  trainig_data,
		success: function(data)
		{
		  	$('.trainingName').html(data.name);
		  	$('#user_cardio_form_burnedCalories').val(data.burnedCalories);
		  	$('#user_cardio_form_trainingId').val(data.trainingId);
			Training.activeAjaxConnections--;
		},
		error: function(jqXHR,  textStatus, errorThrown) {
			Training.activeAjaxConnections--;
		}
	});
}

function checkData(e) {
	let quantity = $('.quantityField').val();
	if(Training.activeAjaxConnections != 0)
	{
		$('#error').html('Obliczam...');
		e.preventDefault();
		e.stopImmediatePropagation();
	}
}
