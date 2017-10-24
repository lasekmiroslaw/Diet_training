var Product = {};
var $hiddenMeal = $('.hiddenMeal').html();
$(`option[value=${$hiddenMeal}]`).attr('selected', 'selected');

$('.productList').on('click', 'li', getNutrientsOnClick);
$('#user_food_form_quantity').on('keyup', getNutrientsOnKeyup);


$('#user_food_form_add').click(
function (e) {
	let quantity = $('#user_food_form_quantity').val();
	if(quantity.match(/^\d{1,6}([\.,]\d{1,2})?$/)) {
		return true;
	}
	else {
		$('#error').html('Podaj prawidłową ilosc');
		e.preventDefault();
		e.stopImmediatePropagation();
	}
});

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
}

function getNutrientsOnKeyup(e) {
	e.stopImmediatePropagation();
	$product_id = Product.$product_id;
	$product_quantity = $('#user_food_form_quantity').val();
	product_data = {
		productId: $product_id,
		productQuantity: $product_quantity
	};

	if($product_quantity.match(/^\d{1,6}([\.,]\d{1,2})?$/)) {
	addNutrients();
	}
}


function addNutrients() {
	const url = $(location).attr('href');
	return $.ajax({
		url:  url,
		type: "POST",
		dataType: "json",
		data:
		  product_data,
		success: function(data)
		{
		  	$('.productInfo').html(data.name);
		  	$('.calories').html(data.calories + 'kcal');
		  	$('.protein').html(data.protein +' g');
		  	$('.carbohydrates').html(data.carbohydrates +' g');
		  	$('.fat').html(data.fat +' g');
			$('#user_food_form_productId').val(data.foodId);
		}
	});
}
