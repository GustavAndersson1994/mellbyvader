<?php

include '../dbConnect.php';
include '../functions.php';


$val = $_GET['datetime'];
$date = date('Y-m-d', strtotime($val));
$interval = $_GET['interval'];



if($interval == "week"){

	//Get closest last monday
	if (date('N', strtotime($val)) != 1){ // 1 for monday, 7 for sunday
		$date = date("Y-m-d", strtotime("last monday " . $date));
	}

	$time_start = strtotime($date);
	$time_end = strtotime($date . " +7days");
	$timeformat = "D";
}
else if($interval == "month"){
	$time_start = strtotime(date('Y-m-01', strtotime($val)));
	$time_end = strtotime(date('Y-m-t', strtotime($val)));
	$timeformat = "d";
}
else if($interval == "year"){
	$time_start = strtotime(date('Y-01-01', strtotime($val)));
	$time_end = strtotime(date('Y-12-t', strtotime($val)));
	$timeformat = "Y-m-d";
}




$tmp_date = $time_start;
$radiation_data = array();
$data = array();

while($tmp_date < $time_end){
	//echo date('Y-m-d 00:00', $tmp_date) . " ";

    $radiation_data["date"] = date($timeformat, $tmp_date);
    $radiation_data["radiation"] = get_radiationsum("interval",
        date('Y-m-d 00:00', $tmp_date),  date('Y-m-d 23:59:59', $tmp_date), $getValues=1);


	$tmp_date = strtotime(date('Y-m-d 00:00', $tmp_date) . "+1day");
	
	$data[] = $radiation_data;
}




//now print the data
print json_encode($data);



?>