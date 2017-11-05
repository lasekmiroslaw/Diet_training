var Product = {};
var $hiddenMeal = $('.hiddenMeal').html();
Product.activeAjaxConnections = 0;

$(`option[value=${$hiddenMeal}]`).attr('selected', 'selected');
$('.productList').on('click', 'li', getNutrientsOnClick);
$('#user_food_form_quantity').keyup(getNutrientsOnKeyup);
$('form[name="user_food_form"]').submit(checkData);

function getNutrientsOnClick(e) {
	$('#user_food_form_quantity').val(100);
	$product_id = $(e.currentTarget).attr('id');
	Product.$product_id = $product_id;
	$product_quantity = $('#user_food_form_quantity').val();
	product_data = {
		productId: $product_id,
		productQuantity: $product_quantity
	};
	e.stopImmediatePropagation();
	addNutrients();

	$(document).ajaxStop(function(){
    	$('.productForm').removeClass('hide');
	});
	return false;
}

function getNutrientsOnKeyup(e) {
	e.stopImmediatePropagation();
	$product_id = Product.$product_id;
	$product_quantity = $('#user_food_form_quantity').val();
	product_data = {
		productId: $product_id,
		productQuantity: $product_quantity
	};
	if($product_quantity.match(/^[1-9][0-9]{0,5}([\.,][0-9]{1,2})?$/)) {
		addNutrients();
	}
}

function addNutrients() {
	const url = $(location).attr('href');
	return $.ajax({
		beforeSend: function(xhr) {
			Product.activeAjaxConnections++;
			},
		url:  url,
		type: "POST",
		dataType: "json",
		data:
		  product_data,
		success: function(data)
		{
		  	$('.productInfo').html(data.name);
		  	$('#user_food_form_calories').val(data.calories);
		  	$('#user_food_form_totalProtein').val(data.protein);
		  	$('#user_food_form_carbohydrates').val(data.carbohydrates);
		  	$('#user_food_form_fat').val(data.fat);
			$('#user_food_form_productId').val(data.foodId);
			Product.activeAjaxConnections--;
		},
		error: function(jqXHR,  textStatus, errorThrown) {
			Product.activeAjaxConnections--;
			$('form[name="user_food_form"]').submit(function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
			});
		}
	});
}

function checkData(e) {
	let quantity = $('#user_food_form_quantity').val();
	if(Product.activeAjaxConnections != 0)
	{
		$('#error').html('Powolutu... przetwarzam');
		e.preventDefault();
		e.stopImmediatePropagation();
	}
	if(quantity.match(/^[1-9][0-9]{0,5}([\.,][0-9]{1,2})?$/)) {
		return true;
	}
	else {
		$('#error').html('Podaj prawidłową ilość');
		e.preventDefault();
		e.stopImmediatePropagation();
	}
}

function debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
};
