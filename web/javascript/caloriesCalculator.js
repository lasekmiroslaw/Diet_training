$('#calculateCaloriesButton').on('click', function(e) {
    let $age = $('#user_data_form_age').val();
    let $weight = $('#user_data_form_weight').val();
    let $height = $('#user_data_form_height').val();
    let $activity = $('#user_data_form_activity').val();
    let $gender = $('#user_data_form_gender').val();
    const url = $(location).attr('href');
    let user_data = {
      age: $age,
      weight: $weight,
      height: $height,
      activity: $activity,
	  gender: $gender,
  };
    e.stopImmediatePropagation();

    $.ajax({
    	url:  url,
    	type: "POST",
    	dataType: "json",
    	data:
    		user_data,
    	success: function(data)
    	{
            console.log(data.user_calories);
    		$('#user_data_form_calories').val(data.user_calories);
    	}
    });
    return false;
});
