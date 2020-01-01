<?php

include 'src/dbConnect.php';


//Get first data
$sql = "SELECT * FROM archive where dateTime = (select max(dateTime) from archive)"; 

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	//Get first timestamp with corresponding data. This timestamp is used to collect the rest of the data. 
	$row = $result->fetch_assoc();
	
	//Dates
	$dateTime = $row["dateTime"];
	$date = date('Y-m-d H:i:s', $dateTime);
	$day = date('Y-m-d', $dateTime);
	$time = date('H:i', $dateTime);
	
	//Temp
	$currentTemp = round($row["outTemp"], 1);
	
	//Rain. DB stores the rain as cm. Convert to mm. 
	$currentRain = round($row["rain"]*10, 2);
	$rainRate = round($row["rainRate"]*10, 2);
	
	//Wind. DB stores the windspeed as km per hour. Convert to m/s.
	$windSpeed = round($row["windSpeed"]/3.6, 1);
	$windDir = round($row["windDir"], 1);
	$windGust = round($row["windGust"]/3.6, 1);
	$windGustDir = round($row["windGustDir"], 1);
		
	
	//Barometer
	$barometer = round($row["barometer"], 1);
	$pressure = round($row["pressure"], 1);
	$altimeter = round($row["altimeter"], 1);
	
	//Sun
	$radiation = round($row["radiation"], 1);
	$UV = round($row["UV"], 1);
	
	
	//Humidity
	$outHumidity = round($row["outHumidity"], 1);
		
	
} else {
    echo "Couldn't get timestamp";
}


//Get max-min values

//TEMP
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_outTemp where dateTime = (select max(dateTime) from archive_day_outTemp)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minTempTime = date('H:i', $row["mintime"]);
	$maxTempTime = date('H:i', $row["maxtime"]);
	$minTemp = round($row["min"], 1);
	$maxTemp = round($row["max"], 1);
	
} else {
    echo "Couldn't get max/min temp";
}



//RAIN
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_rainRate where dateTime = (select max(dateTime) from archive_day_rainRate)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minRainTime = date('H:i', $row["mintime"]);
	$maxRainTime = date('H:i', $row["maxtime"]);
	$minRain = round($row["min"]*10, 2);
	$maxRain = round($row["max"]*10, 2);

	
} else {
    echo "Couldn't get max/min Rain";
}

$sql = "SELECT sum FROM archive_day_rain where dateTime = (select max(dateTime) from archive_day_rain)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$rainSum = round($row["sum"]*10, 2); //Convert cm to mm 

	
} else {
    echo "Couldn't get Rain sum";
}





//Barometer
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_barometer where dateTime = (select max(dateTime) from archive_day_barometer)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minBarometerTime = date('H:i', $row["mintime"]);
	$maxBarometerTime = date('H:i', $row["maxtime"]);
	$minBarometer = round($row["min"], 1);
	$maxBarometer = round($row["max"], 1);
	
} else {
    echo "Couldn't get max/min Barometer";
}



//WIND GUST
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_windGust where dateTime = (select max(dateTime) from archive_day_windGust)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minWindGustTime = date('H:i', $row["mintime"]);
	$maxWindGustTime = date('H:i', $row["maxtime"]);
	$minWindGust = round($row["min"]/3.6, 1);
	$maxWindGust = round($row["max"]/3.6, 1);
	
} else {
    echo "Couldn't get max/min WindGust";
}

$sql = "SELECT min, mintime, max, maxtime FROM archive_day_windGustDir where dateTime = (select max(dateTime) from archive_day_windGustDir)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minWindGustDirTime = date('H:i', $row["mintime"]);
	$maxWindGustDirTime = date('H:i', $row["maxtime"]);
	$minWindGustDir = round($row["min"], 1);
	$maxWindGustDir = round($row["max"], 1);
	
} else {
    echo "Couldn't get max/min WindGust";
}


//UV
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_UV where dateTime = (select max(dateTime) from archive_day_UV)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minUVTime = date('H:i', $row["mintime"]);
	$maxUVTime = date('H:i', $row["maxtime"]);
	$minUV = round($row["min"], 1 );
	$maxUV = round($row["max"], 1 );
	
} else {
    echo "Couldn't get max/min UV";
}


//RADIATION
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_radiation where dateTime = (select max(dateTime) from archive_day_radiation)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minRadiationTime = date('H:i', $row["mintime"]);
	$maxRadiationTime = date('H:i', $row["maxtime"]);
	$minRadiation = round($row["min"], 0 );
	$maxRadiation = round($row["max"], 0 );
	
} else {
    echo "Couldn't get max/min Radiation";
}



//SUM OF RADIATION
$start_time = strtotime(date("Y-m-d 00:00"));
$now = strtotime(date("Y-m-d H:m"));
$sql = "SELECT SUM(radiation) AS wattsum FROM archive WHERE dateTime >= '$start_time' and dateTime <= '$now'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $watt_sum = round($row["wattsum"] * 600 / 3600000, 1 );

} else {
    echo "Couldn't get sum of Radiation ";
}




//Humidity
$sql = "SELECT min, mintime, max, maxtime FROM archive_day_outHumidity where dateTime = (select max(dateTime) from archive_day_outHumidity)"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();
	
	$minHumidityTime = date('H:i', $row["mintime"]);
	$maxHumidityTime = date('H:i', $row["maxtime"]);
	$minHumidity = round($row["min"], 1);
	$maxHumidity = round($row["max"], 1);
	
} else {
    echo "Couldn't get max/min Humidity";
}


$conn->close();


?>