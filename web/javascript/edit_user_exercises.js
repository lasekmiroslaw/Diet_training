//Finding pattern and removing extra text
// $('li.training-list').contents().each(function(){
//   var element = $(this);
//   if(element.html()){
// 	  element.html(element.html().replace(/-[0-9]{1,6}-/g,''));
//   }
// });
$(document).ready(function() {

	$('.training-list').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(this).find('.toggleHide').toggleClass('hide');
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
