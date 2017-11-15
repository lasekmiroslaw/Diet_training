var Product = {};
var $hiddenMeal = $('.hiddenMeal').html();
Product.activeAjaxConnections = 0;

$(`option[value=${$hiddenMeal}]`).attr('selected', 'selected');
$('.productList').on('click', 'li', getNutrientsOnClick);
$('.quantityField').keyup(getNutrientsOnKeyup);
$('.submitField').click(checkData);


function getNutrientsOnClick(e) {
	$('.quantityField').val(100);
	$product_id = $(e.currentTarget).attr('id');
	Product.$product_id = $product_id;
	$product_quantity = $('.quantityField').val();
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
	$product_quantity = $('.quantityField').val();
	product_data = {
		productId: $product_id,
		productQuantity: $product_quantity
	};
	if($product_quantity.match(/^[1-9][0-9]{0,5}([\.,][0-9]{1,2})?$/)) {
		setTimeout(addNutrients, 800);
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
		  	$('.caloriesField').val(data.calories);
		  	$('.proteinField').val(data.protein);
		  	$('.carbohydratesField').val(data.carbohydrates);
		  	$('.fatField').val(data.fat);
			$('.hiddenType').val(data.foodId);
			Product.activeAjaxConnections--;
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
