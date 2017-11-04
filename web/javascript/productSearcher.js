var searchRequest = null;
$("#search_form_name").keyup(function(e) {
	e.stopImmediatePropagation();
	var minlength = 3;
	var that = this;
	var value = $(this).val();
	var entitySelector = $("#entitiesNav").html('');
	var $url = $('.sidebar-search').data('path');
	if (value.length >= minlength ) {
		if (searchRequest != null)
			searchRequest.abort();
		searchRequest = $.ajax({
			type: "GET",
			url: $url,
			data: {
				'foundProducts' : value
			},
			dataType: "text",
			success: function(msg){
				//we need to check if the value is the same
				if (value==$(that).val()) {
					var result = JSON.parse(msg);
					$.each(result, function(key, arr) {
						$.each(arr, function(id, value) {
							if (key == 'entities') {
								if (id != 'error') {
									entitySelector.append(`<li><a href=${$url}/${id}>${value}</a></li>`);
									$('#foodCategory').addClass('hide');
								} else {
									entitySelector.append('<li class="errorLi">'+value+'</li>');
									$('#foodCategory').removeClass('hide');
								}
							}
						});
					});
				}
			 }
		});
	}
	if(value.length < minlength) {
		$('#foodCategory').removeClass('hide');
	}
});
