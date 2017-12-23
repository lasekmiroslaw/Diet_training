$(document).ready(function() {

	$('li.training-list > a.training-link').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).parent().find('.toggleHide').toggleClass('hide');
	});
	$('.exercise').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
	});
	$('.exercise-link').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).parent().siblings('.seriesDiv').toggleClass('hide');
	});
});
