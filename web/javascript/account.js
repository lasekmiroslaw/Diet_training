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
	document.querySelector('.editEmail').addEventListener("click", function(e)
	{
		e.preventDefault();
		document.querySelector('#email_form_email').removeAttribute("readonly");
		document.querySelector('.emailBtn').setAttribute('type', 'submit');
	});
	document.querySelector('.showForm').addEventListener('click', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		document.querySelector('.hiddenForm').classList.toggle('hide');
	});
}, false);
