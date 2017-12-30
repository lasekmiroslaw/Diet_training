var $currentCalories = $('span.currentCalories').text();
var $dailyCalories = $('span.dailyCalories').text();
var $protein = $('span.protein').text();
var $carbohydrates = $('span.carbohydrates').text();
var $fat = $('span.fat').text();

addPercentValues();

function addPercentValues() {
    var $percentCalories = calculatePercentShare($currentCalories, $dailyCalories);
    var $percentProtein = calculatePercentShare($protein, $currentCalories, 4);
    var $percentCarbohydrates = calculatePercentShare($carbohydrates, $currentCalories, 4);
    var $percentFat = calculatePercentShare($fat, $currentCalories, 9);

    $('div#graph').attr('data-percent', $percentCalories);
    $('span.centerText').text($percentCalories + '%');
    $('span.pProtein').text($percentProtein + '%');
    $('span.pCarbohydrates').text($percentCarbohydrates + '%');
    $('span.pFat').text($percentFat + '%');
}

function calculatePercentShare($value, $totalValue, $factor = 1) {
	if($totalValue == 0)
	{
		$totalValue = 1;
	}
	$percentShare = Math.round((($value*$factor)/$totalValue)*100);
	return $percentShare;
}
