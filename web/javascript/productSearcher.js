var searchRequest = null;
$("#search").keyup(function(e) {
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
				'q' : value
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
									entitySelector.append('<li><a href="/daten/'+id+'">'+value+'</a></li>');
								} else {
									entitySelector.append('<li class="errorLi">'+value+'</li>');
								}
							}
						});
					});
				}
			 }
		});
	}
});
