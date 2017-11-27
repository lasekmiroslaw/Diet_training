document.getElementById('cardio').addEventListener('click', function(e) {
	e.stopImmediatePropagation();
	document.getElementById('cardioCategory').classList.toggle("hide");
});;

document.getElementById('strength').addEventListener('click', function(e) {
	e.stopImmediatePropagation();
	document.getElementById('strengthCategory').classList.toggle("hide");
});;
