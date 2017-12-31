var Training = {};
Training.activeAjaxConnections = 0;
var $caloriesper60;

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
	$training_time = eval(($('input#user_cardio_form_time_hour').val())*60) + eval($('input#user_cardio_form_time_minute').val());
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
	$minutes = timeInMinutes();
	$burnedCalories = calculatePerTime($caloriesper60, $minutes)
	$('#user_cardio_form_burnedCalories').val($burnedCalories);
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
		  	$('td.trainingName').html(data.name);
		  	$('input#user_cardio_form_burnedCalories').val(data.burnedCalories);
		  	$('input#user_cardio_form_trainingId').val(data.trainingId);
			Training.activeAjaxConnections--;
			$caloriesper60 = data.burnedCalories*2;
		},
		error: function(jqXHR,  textStatus, errorThrown) {
			Training.activeAjaxConnections--;
		}
	});
}

function timeInMinutes()
{
	$time = [];
	$time['hour'] = $('select#user_cardio_form_time_hour').val();
	$time['minute'] = $('select#user_cardio_form_time_minute').val();
	$minute = parseInt(($time['hour']*60)) + parseInt($time['minute']);

	return $minute;
}

function calculatePerTime($caloriesper60, $minute)
{
	$caloriesPerTime = (($caloriesper60/60) * $minute).toFixed(1);
	return $caloriesPerTime;
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
