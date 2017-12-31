var Product = {};
var $hiddenMeal = $('.hiddenMeal').html();

$(`option[value=${$hiddenMeal}]`).attr('selected', 'selected');
$('#user_food_form_quantity').keyup(getNutrientsOnKeyup);
$('#user_food_form_add').click(checkData);

window.onload = function () {
	$('input.quantityField').val(100);
	$('input.caloriesField').val($('input.caloriesField').attr('data'));
	$('input.proteinField').val($('input.proteinField').attr('data'));
	$('input.carbohydratesField').val($('input.carbohydratesField').attr('data'));
	$('input.fatField').val($('input.fatField').attr('data'));
	$calories = $('input.caloriesField').val();
	$protein = $('input.proteinField').val();
	$carbohydrates = $('input.carbohydratesField').val();
	$fat = $('input.fatField').val();
	$product_quantity = $('input.quantityField').val();
}


function getNutrientsOnKeyup(e) {
	e.stopImmediatePropagation();
	$product_quantity = $('input.quantityField').val();
	if(isNaN($product_quantity) === false) {
		$('input.caloriesField').val(calculateNutrient($calories, $product_quantity));
		$('input.proteinField').val(calculateNutrient($protein, $product_quantity));
		$('input.carbohydratesField').val(calculateNutrient($carbohydrates, $product_quantity));
		$('input.fatField').val(calculateNutrient($fat, $product_quantity));
	}
}

function checkData(e) {
	let quantity = $('#user_food_form_quantity').val();
	if(quantity.match(/^[1-9][0-9]{0,5}([\.,][0-9]{1,2})?$/)) {
		return true;
	}
	else {
		$('#error').html('Podaj prawidłową ilość');
		e.preventDefault();
		e.stopImmediatePropagation();
	}
}

function calculateNutrient($productPer100, $productQuantity)
{
	$productPerQuantity = ((($productPer100 * $productQuantity)/100)).toFixed(1);
	return $productPerQuantity;
}
