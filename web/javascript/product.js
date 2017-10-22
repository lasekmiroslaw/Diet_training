var Product = {};
$('.productList').on('click', 'li', function(e) {
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
});

$('#user_food_form_quantity').on('keyup', ajaxGetNutrients);

var $hiddenMeal = $('.hiddenMeal').html();

$(`option[value=${$hiddenMeal}]`).attr('selected', 'selected');


function ajaxGetNutrients(e) {
	e.stopImmediatePropagation();
	$product_id = Product.$product_id;
	$product_quantity = $('#user_food_form_quantity').val();
	product_data = {
		productId: $product_id,
		productQuantity: $product_quantity
	};
	addNutrients();
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
			console.log(data.foodId);
		}
	});
}
