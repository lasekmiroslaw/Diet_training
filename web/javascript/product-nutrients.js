var Product = {};
var $hiddenMeal = $('.hiddenMeal').html();
Product.activeAjaxConnections = 0;
var $calories;
var $protein;
var $carbohydrates;
var $fat;

$(`option[value=${$hiddenMeal}]`).attr('selected', 'selected');
$('.productList').on('click', 'li', getNutrientsOnClick);
$('.quantityField').keyup(getNutrientsOnKeyup);
$('.submitField').click(checkData);


function getNutrientsOnClick(e) {
	$('.quantityField').val(100);
	$product_id = $(e.currentTarget).attr('id');
	Product.$product_id = $product_id;
	product_data = {
		productId: $product_id,
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
	let	$product_quantity = $('.quantityField').val();

	if(isNaN($product_quantity) === false) {
		$('input.caloriesField').val(calculateNutrient($calories, $product_quantity));
		$('input.proteinField').val(calculateNutrient($protein, $product_quantity));
		$('input.carbohydratesField').val(calculateNutrient($carbohydrates, $product_quantity));
		$('input.fatField').val(calculateNutrient($fat, $product_quantity));
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
			console.log(data)
		  	$('.productInfo').html(data.name);
		  	$('input.caloriesField').val(data.calories);
		  	$('input.proteinField').val(data.protein);
		  	$('input.carbohydratesField').val(data.carbohydrates);
		  	$('input.fatField').val(data.fat);
			$('.hiddenType').val(data.foodId);
			Product.activeAjaxConnections--;
			$calories = $('.caloriesField').val();
			$protein = $('.proteinField').val();
			$carbohydrates = $('.carbohydratesField').val();
			$fat = $('input.fatField').val();
		},
		error: function(jqXHR,  textStatus, errorThrown) {
			Product.activeAjaxConnections--;
		}
	});
}

function checkData(e) {
	let quantity = $('.quantityField').val();
	if(Product.activeAjaxConnections != 0)
	{
		$('#error').html('Obliczam...');
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

function calculateNutrient($productPer100, $productQuantity)
{
	$productPerQuantity = ((($productPer100 * $productQuantity)/100)).toFixed(1);
	return $productPerQuantity;
}
