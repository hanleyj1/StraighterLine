<?php
//clean_input function takes a parameter and cleans it of harmful lines of code.
function clean_input($input) {
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   /* strip out javascript */
		'@<[\/\!]*?[^<>]*?>@si',            /* strip out HTML tags */
		'@<style[^>]*?>.*?</style>@siU',    /* strip style tags properly */
		'@<![\s\S]*?--[ \t\n\r]*>@'         /* strip multi-line comments */
	);

	$output = preg_replace($search, '', $input);
	return $output;
}
$allLatLong=array(); // Global array created to store latitude and longitude for each selected city
$navbarLinks=''; // Global variable for storing links to be added to the navbar
/*
getCityWeather pulls in JSON data from the Yahoo Weather API. An object is created and then used to pull out weather information for each selected city.

*/
function getCityWeather($cities, $zipCodes) {
	global $allLatLong, $navbarLinks;
	$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
    $yql_query = 'select * from weather.forecast where location in ('.$zipCodes.')';
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
    // Make call with cURL
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    // Convert JSON to PHP object
    $weatherObj =  json_decode($json);
	$count=$weatherObj->query->count;
	$html='';
	
	//for loop iterates through all selected cities returned by Yahoo 
	for($x=0;$x<$count;$x++){
		if($count>1){
			//if more than 1 city is selected, the array for the city must be specified
			$channel = $weatherObj->query->results->channel[$x];
		}
		else{
			$channel = $weatherObj->query->results->channel;
		}
		if($x==0){
			$unitsOfDegree=$channel->units->temperature;
			$unitsOfSpeed=$channel->units->speed;
			$unitsOfPressure=$channel->units->pressure;
			$unitsOfDistance=$channel->units->distance;
			$display='';
			$active='active';
		}
		else{
			$display='displayNone';
			$active='';
		}
		$buildDateTime=$channel->lastBuildDate;
		$cityName=$channel->location->city;
		$state=$channel->location->region;
		$zipCode=$cities[$x];
		$windDir=degToCompass($channel->wind->direction);
		$windSpeed=$channel->wind->speed;
		$humidity=$channel->atmosphere->humidity;
		$pressure=$channel->atmosphere->pressure;
		$visibility=$channel->atmosphere->visibility;
		$sunrise=$channel->astronomy->sunrise;
		$sunset=$channel->astronomy->sunset;
		$temp=$channel->item->condition->temp;
		$conditionType=$channel->item->condition->text;
		$latitude=$channel->item->lat;
		$longitude=$channel->item->long;
		$navbarLinks.='<li class="'.$active.'"><a href="#" view="'.$zipCode.'" class="navbarSelected ">'.$cityName.', '.$state.'</a></li>';
		$latLong= array (
			"latitude" => $latitude,
			"longitude" => $longitude);
		$allLatLong[$x] = $latLong;
		$html.='<div class="col-xs-12 col-sm-12 col-md-12 cityBody '.$display.' active" id="'.$zipCode.'"><div class="row"><div class="col-xs-12 col-sm-8 col-md-8 cityForecast"><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 forecastHeader "><h3 class="well well-lg">Weather for: '.$cityName.', '.$state.' ('.$zipCode.')<br><span class="build">As of '.$buildDateTime.'</span></h3></div><div class="col-xs-12 col-sm-6 col-md-6"><div><h5>Currently:</h5><p><span class="temperature">'.$temp.'&deg; '.$unitsOfDegree.'</span><br><span class="condition">'.$conditionType.'</span></p></div><table class="sunRiseSet"><tr class="label-warning"><th>Sunrise:</th><th>'.$sunrise.'</th></tr><tr class="label-primary"><th>Sunset:</th><th>'.$sunset.'</th></tr></table></div><div class="col-xs-12 col-sm-6 col-md-6"><table class="otherConditions table table-hover"><tr><th>Wind:</th><td>'.$windDir.' '.$windSpeed.' '.$unitsOfSpeed.'</td></tr><tr><th>Humidity:</th><td>'.$humidity.'</td></tr><tr><th>Pressure:</th><td>'.$pressure.' '.$unitsOfPressure.'</td></tr><tr><th>Visibility:</th><td>'.$visibility.' '.$unitsOfDistance.'</td></tr></table></div><div class="col-xs-12 col-sm-12 col-md-12 "><div class="dailyForecast"><h4>5 Day Forecast</h4><table class="table table-striped table-condensed"><tr><th>Day</th><th>Conditions</th><th>High/Low</th></tr>';
					for($y=0;$y<5;$y++){
						$day=$channel->item->forecast[$y]->day;
						$date=$channel->item->forecast[$y]->date;
						$dates = explode(" ", $date);
						$dateNum=$dates[0];
						$dateMon=$dates[1];
						$high=$channel->item->forecast[$y]->high;
						$low=$channel->item->forecast[$y]->low;
						$dailyCondition=$channel->item->forecast[$y]->text;
						if($dailyCondition=='Sunny'){
							$dailyCondition='<span class="glyphicon glyphicon-sunglasses yellow"></span> '.$dailyCondition;
						}
						if($dailyCondition=='Mostly Sunny'){
							$dailyCondition='<span class="glyphicon glyphicon-sunglasses blue"></span> '.$dailyCondition;
						}
						elseif($dailyCondition=='Rain' || $dailyCondition=='Rainy' || $dailyCondition=='Thunderstorms'){
							$dailyCondition='<span class="glyphicon glyphicon-tint"></span> '.$dailyCondition;
						}
						elseif($dailyCondition=='Mostly Cloudy'){
							$dailyCondition='<span class="glyphicon glyphicon-cloud darkGrey"></span> '.$dailyCondition;
						}
						elseif($dailyCondition=='Cloudy' || $dailyCondition=='Partly Cloudy'){
							$dailyCondition='<span class="glyphicon glyphicon-cloud grey"></span> '.$dailyCondition;
						}
						
						$html.='<tr><td>'.$day.'<br>'.$dateMon.' '.$dateNum.'</td><td>'.$dailyCondition.'</td><td>'.$high.'&deg;/'.$low.'&deg;</td></tr>';
					}
					$html.='</table></div></div></div></div><div class="col-xs-12 col-sm-4 col-md-4" ><span class="visible-sm-block visible-md-block visible-lg-block locationMap" id="map_'.$x.'"></span></div></div></div>';
	}
	return $html;
}

//degToCompass is called and provided a number of degrees. This is then translated into a letter to show direction
function degToCompass($dirDegrees)
{
	$direction='';
	$directions = array(
	'N' => array(337.5, 22.5),
	'NE' => array(22.5, 67.5),
	'E' => array(67.5, 112.5),
	'SE' => array(112.5, 157.5),
	'S' => array(157.5, 202.5),
	'SW' => array(202.5, 247.5),
	'W' => array(247.5, 292.5),
	'NW' => array(292.5, 337.5)
	);

	foreach ($directions as $dir => $angles) {
		if ($dirDegrees >= $angles[0] && $dirDegrees < $angles[1]) {
		$direction = $dir;
		break;
		}
	}
return $direction;
}


$zipCodes='';
$weather='';
$total=0;
if(isset($_POST['city'])){
	$total=count($_POST['city']);
	for($x=0;$x<$total;$x++){
		if($x!=0){
			$zipCodes.=', ';
		}
			$zipCodes.=clean_input($_POST['city'][$x]);
	}
	$weather=getCityWeather($_POST['city'], $zipCodes);
}
$data = array(	"total"     => $total,
				"weather"     => $weather,
				"navbar"     => $navbarLinks,
				"latLong"  => $allLatLong);				
				
echo json_encode($data);
?>

