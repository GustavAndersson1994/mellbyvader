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





//query to get data from the table
$query = "SELECT dateTime, outTemp, rain, barometer, radiation, UV, " . 
		 " outHumidity, windSpeed, windDir, windGust, windGustDir " .
				"FROM archive " .
				"where dateTime >= '$time_start' and dateTime < '$time_end' and dateTime != 'NULL'";


//execute query
$result = $conn->query($query);

//loop through the returned data
$data = array();

$i = 0;
foreach ($result as $row) {
  
	if($i % $data_pts == 0){
  		$row['dateTime'] = date($timeformat, $row['dateTime']);
		$row['outTemp'] = number_format($row['outTemp'], 1);
		$row['rain'] = number_format($row['rain']*10, 2);
		$row['barometer'] = number_format(round($row['barometer'], 2), 2, '.', '');
		$row['radiation'] = round($row['radiation'], 2);
		$row['UV'] = number_format(round($row['UV'], 2), 2, '.', '');
		$row['outHumidity'] = round($row['outHumidity'], 2);
		$row['windSpeed'] = number_format(round($row['windSpeed']/3.6, 2), 2, '.', '');
		$row['windDir'] = number_format(round($row['windDir'], 2), 2, '.', '');
		$row['windGust'] = number_format(round($row['windGust']/3.6, 2), 2, '.', '');
		$row['windGustDir'] = round($row['windGustDir'], 2);

  		$data[] = $row;
  	}
  	$i++;

}


//free memory associated with result
$result->close();

//close connection
$conn->close();

//now print the data
print json_encode($data);



?>