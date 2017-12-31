var addMore = document.querySelector('.addMore');
var hiddenInfo = document.querySelector('.hiddenInfo');
var moreInfo = document.querySelector('.moreInfo');

moreInfo.appendChild(hiddenInfo);
addMore.addEventListener('click', function(e) {
	e.stopImmediatePropagation();
	hiddenInfo.classList.toggle("hide");
});
