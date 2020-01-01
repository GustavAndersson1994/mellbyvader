<?php

include '../dbConnect.php';
include '../functions.php';


$val = $_GET['datetime'];
$date = date('Y-m-d', strtotime($val));
$interval = $_GET['interval'];


if ($interval == "day"){
	if($date == date('Y-m-d', strtotime("today")) || !isset($val)){
		$date = date('Y-m-d H:i', strtotime("now"));
		$time_start = strtotime($date . "-1day");
		$time_end = strtotime($date);
	} else{
		$time_start = strtotime(date('Y-m-d 00:00', strtotime($date)));
		$time_end = strtotime(date('Y-m-d 23:59:59', strtotime($date)));
	} 
	$data_pts = 1;
	$timeformat = "H:i";
}
else if($interval == "week"){

	//Get closest last monday
	if (date('N', strtotime($val)) != 1){ // 1 for monday, 7 for sunday
		$date = date("Y-m-d", strtotime("last monday " . $date));
	}
	
	$time_start = strtotime(date('Y-m-d 00:00', strtotime($date)));
	$time_end = strtotime(date('Y-m-d 00:00:00', strtotime($date . " +7days")));
	$data_pts = 6;
	$timeformat = "d/m H:i";
}
else if($interval == "month"){
	$time_start = strtotime(date('Y-m-01 00:00', strtotime($val)));
	$time_end = strtotime(date('Y-m-t 23:59:59', strtotime($val)));
	$data_pts = 36;
	$timeformat = "d/m H:i";
}
else if($interval == "year"){
	$time_start = strtotime(date('Y-01-01 00:00', strtotime($val)));
	$time_end = strtotime(date('Y-12-31 23:59:59', strtotime($val)));
	$data_pts = 72;
	$timeformat = "d/m H:i";
}


$time_start = date('Y-m-d H:i', $time_start);
$time_end = date('Y-m-d H:i', $time_end);


$table_name_arr = array("outTemp", "barometer", "radiation", "UV", "outHumidity");
$interval = "interval";
$unit_arr = array("°C", "mbar", "W/m²", "UV", "%");


$data = [];
for ($i = 0; $i < count($table_name_arr); $i++) {
	$max = get_min_max($table_name_arr[$i], $interval, "max", $unit_arr[$i], $time_start, $time_end);
	$min = get_min_max($table_name_arr[$i], $interval, "min", $unit_arr[$i], $time_start, $time_end);
	
	$data[$table_name_arr[$i]] = [$max, $min];
}


$data["rain"] = get_rainsum($interval, $time_start, $time_end);
$data["wind"] = get_max_wind($interval, $time_start, $time_end, 1, "Y-m-d H:i");
$data["wind_gust"] = get_max_wind_gust($interval, $time_start, $time_end, 1, "Y-m-d H:i");




//now print the data
print json_encode($data);



?>



