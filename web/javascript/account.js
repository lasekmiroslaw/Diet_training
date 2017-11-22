document.addEventListener('DOMContentLoaded', function(e) {
	document.getElementById('image_form_profileImage').addEventListener("change", function(e)
	{
		e.stopImmediatePropagation();
		document.querySelector('form[name="image_form"]').submit();
	});
	e.stopImmediatePropagation();

	document.getElementById('editData').addEventListener("click", function(e)
	{
		e.preventDefault();
		for (var i = 0; i < 3; i++) {
			document.querySelectorAll('.narrowFields')[i].removeAttribute("readonly");
			document.querySelector('.btn.btn-success').setAttribute('type', 'submit');
		}
	});

}, false);
