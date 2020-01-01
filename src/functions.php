<?php



//Generic function to get data from a max-min value
function get_min_max($table_name, $interval, $max_or_min, $unit, $start='', $end=''){
	include 'dbConnect.php';

	
	//Set timezone to be sure that convertion from unix_time is correct
	date_default_timezone_set('Europe/Stockholm');
	
	if($interval == "week"){
		$time_start = strtotime('Monday this week');
		$time_end = strtotime("next Monday");
	}
	
	else if($interval == "month"){
		$time_start = strtotime(date('Y-m-01'));
		$time_end = strtotime(date('Y-m-t'));
	}
	
	else if($interval == "year"){
		$time_start = strtotime(date('Y-01-01'));
		$time_end = strtotime(date('Y-12-31'));
	}
	
	else if($interval == "interval"){
		$time_start = strtotime($start);
		$time_end = strtotime($end); 
	}
	
	
	
	if($max_or_min == "max"){
	
		//max
		$sql = "SELECT max as value, maxtime as time " .
				"FROM archive_day_" . $table_name . " " .
				"where max !='NULL' " .
				"and dateTime >= '$time_start' and dateTime <= '$time_end' " .
				"ORDER BY max DESC " .
				"LIMIT 1" ;
	}
	else if($max_or_min == "min"){
		//min
		$sql = "SELECT min as value, mintime as time " .
				"FROM archive_day_" . $table_name . " " .
				"where min !='NULL' " .
				"and dateTime >= '$time_start' and dateTime <= '$time_end' " .
				"ORDER BY min ASC " .
				"LIMIT 1";	
	}
	 	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		
		$time = date('Y-m-d H:i', $row["time"]);
		$value = round($row["value"], 1);	
						
		return $value . " " . $unit . " kl. " . $time;
	
	}else {
    	return "Couldn't get max/min value";	
	}
	$conn->close();
}


//Get total rain during an interval
function get_rainsum($interval, $start='', $end='', $getValues=0){
	include 'dbConnect.php';

	
	//Set timezone to be sure that convertion from unix_time is correct
	date_default_timezone_set('Europe/Stockholm');
	
	if($interval == "week"){
		$time_start = strtotime('Monday this week');
		$time_end = strtotime("next Monday");
	}
	
	else if($interval == "month"){
		$time_start = strtotime(date('Y-m-01'));
		$time_end = strtotime(date('Y-m-t'));
	}
	
	else if($interval == "year"){
		$time_start = strtotime(date('Y-01-01'));
		$time_end = strtotime(date('Y-12-31'));
	}
	
	else if($interval == "interval"){
		$time_start = strtotime($start);
		$time_end = strtotime($end); 
	}
	
	
	$sql = "SELECT SUM(sum) AS value FROM archive_day_rain " .
			"WHERE dateTime >= '$time_start' and dateTime <= '$time_end'";
	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		
		$value = round($row["value"]*10, 1); //Convert cm to mm

		if($getValues){
			return $value; 
		}
				
		return "Totalt regn denna period: " . $value . " mm";
	
	}else {
    	return "Couldn't get rain data";	
	}
	$conn->close();
}

//Get total radiation during an interval
function get_radiationsum($interval, $start='', $end='', $getValues=0){
    include 'dbConnect.php';


    //Set timezone to be sure that convertion from unix_time is correct
    date_default_timezone_set('Europe/Stockholm');

    if($interval == "week"){
        $time_start = strtotime('Monday this week');
        $time_end = strtotime("next Monday");
    }

    else if($interval == "month"){
        $time_start = strtotime(date('Y-m-01'));
        $time_end = strtotime(date('Y-m-t'));
    }

    else if($interval == "year"){
        $time_start = strtotime(date('Y-01-01'));
        $time_end = strtotime(date('Y-12-31'));
    }

    else if($interval == "interval"){
        $time_start = strtotime($start);
        $time_end = strtotime($end);
    }

    $sql = "SELECT SUM(radiation) AS value FROM archive " .
        "WHERE dateTime >= '$time_start' and dateTime <= '$time_end'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $value = round($row["value"] * 600 / 3600000,2);

        if($getValues){
            return $value;
        }

        return "Totalt idag: " . $value . " kWh/m²";

    }else {
        return "Couldn't get radiation data";
    }
    $conn->close();
}



//Generic function to get data from a max-min value
function get_max_wind($interval, $start='', $end='', $index=0, $timeFormat){
	include 'dbConnect.php';

	
	//Set timezone to be sure that convertion from unix_time is correct
	date_default_timezone_set('Europe/Stockholm');
	

	if($interval == "day"){
		$time_start = strtotime('today');
		$time_end = strtotime('tomorrow');
	}	
	
	else if($interval == "week"){
		$time_start = strtotime('Monday this week');
		$time_end = strtotime("next Monday");
	}
	
	else if($interval == "month"){
		$time_start = strtotime(date('Y-m-01'));
		$time_end = strtotime(date('Y-m-t'));
	}
	
	else if($interval == "year"){
		$time_start = strtotime(date('Y-01-01'));
		$time_end = strtotime(date('Y-12-31'));
	}
	else if($interval == "interval"){
		$time_start = strtotime($start);
		$time_end = strtotime($end); 
	}
	
	
	
	$sql = "SELECT windSpeed, windDir, dateTime " . 
			"FROM archive WHERE dateTime >= '$time_start' and dateTime <= '$time_end' and windSpeed !='NULL' " . 
			"ORDER BY windSpeed DESC LIMIT 1";  //DESC FOR MAX VALUE

	 	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		
		$maxWindTime = date($timeFormat, $row["dateTime"]);
		$maxWind = round($row["windSpeed"]/3.6, 1);	//convert km/h to m/s
		$maxDir = round($row["windDir"], 1);	
		
		if(!$index)
			return array("maxWindTime"=>$maxWindTime, "maxWind"=>$maxWind, "maxDir"=>$maxDir);
		else				
			return number_format($maxWind,1) . " m/s från " . wind_cardinals($maxDir) . " kl. " . $maxWindTime ; 
						
							
	}else {
    	return "Couldn't get max/min value";	
	}
	$conn->close();
}


//Generic function to get data from a max-min value
function get_max_wind_gust($interval, $start='', $end='', $index=0, $timeFormat){
	include 'dbConnect.php';

	
	//Set timezone to be sure that convertion from unix_time is correct
	date_default_timezone_set('Europe/Stockholm');
	

	if($interval == "day"){
		$time_start = strtotime('today');
		$time_end = strtotime('tomorrow');
	}	
	
	else if($interval == "week"){
		$time_start = strtotime('Monday this week');
		$time_end = strtotime("next Monday");
	}
	
	else if($interval == "month"){
		$time_start = strtotime(date('Y-m-01'));
		$time_end = strtotime(date('Y-m-t'));
	}
	
	else if($interval == "year"){
		$time_start = strtotime(date('Y-01-01'));
		$time_end = strtotime(date('Y-12-31'));
	}
	else if($interval == "interval"){
		$time_start = strtotime($start);
		$time_end = strtotime($end); 
	}
	
	
	
	$sql = "SELECT windGust, windGustDir, dateTime " . 
			"FROM archive WHERE dateTime >= '$time_start' and dateTime <= '$time_end' and windGust !='NULL' " . 
			"ORDER BY windGust DESC LIMIT 1";  //DESC FOR MAX VALUE

	 	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		
		$maxWindTime = date($timeFormat, $row["dateTime"]);
		$maxWind = round($row["windGust"]/3.6, 1);	//convert km/h to m/s
		$maxDir = round($row["windGustDir"], 1);	
		
		if(!$index)
			return array("maxWindTime"=>$maxWindTime, "maxWind"=>$maxWind, "maxDir"=>$maxDir);
		else				
			return number_format($maxWind,1) . " m/s från " . wind_cardinals($maxDir) . " kl. " . $maxWindTime ; 
						
							
	}else {
    	return "Couldn't get max/min value";	
	}
	$conn->close();
}




//Get compass direction
function wind_cardinals($deg) {

	if($deg > 360)
		$deg = $deg -360; 

	$cardinalDirections = array(
		'N' => array(348.75, 360),
		'N2' => array(0, 11.25),
		'NNÖ' => array(11.25, 33.75),
		'NÖ' => array(33.75, 56.25),
		'ÖNÖ' => array(56.25, 78.75),
		'Ö' => array(78.75, 101.25),
		'ÖSÖ' => array(101.25, 123.75),
		'SÖ' => array(123.75, 146.25),
		'SSÖ' => array(146.25, 168.75),
		'S' => array(168.75, 191.25),
		'SSV' => array(191.25, 213.75),
		'SV' => array(213.75, 236.25),
		'VSV' => array(236.25, 258.75),
		'V' => array(258.75, 281.25),
		'VNV' => array(281.25, 303.75),
		'NV' => array(303.75, 326.25),
		'NNV' => array(326.25, 348.75)
	);
	foreach ($cardinalDirections as $dir => $angles) {
			if ($deg >= $angles[0] && $deg < $angles[1]) {
				$cardinal = str_replace("2", "", $dir);
			}
		}
		return $cardinal;
}



//Check if the date is valid
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}





