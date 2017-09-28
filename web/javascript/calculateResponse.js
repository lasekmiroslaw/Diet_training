$(document).on('click', 'calculateCaloriesButton', function(){
	$.ajax({
		url:  'localhost/dieta_trening/web/app_dev.php/data_form',
		type: "POST",
		dataType: "json",
		data: {
			"some_var_name": "some_var_value"
		},
		async: true,
		success: function (data)
		{
			console.log(data)
			$('div#ajax-results').html('guwno');

		}
	});
	return false;

});
