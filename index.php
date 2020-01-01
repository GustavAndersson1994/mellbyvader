<!-- Created by Gustav Andersson 2019-05-04 --!>
<?php

    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');

	include 'src/dbData.php';
	include 'src/functions.php';


    $im = imagecreatefromjpeg('../vaderbilder/aktuell.jpg');
    $size = min(imagesx($im), imagesy($im));
    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 1550, 'height' => 1010]);
    if ($im2 !== FALSE) {
        imagejpeg($im2, '../vaderbilder/aktuell.jpg');
        imagedestroy($im2);
    }
    imagedestroy($im);


	//Red or blue thermo?
	if($currentTemp > 0)
		$tempColor ="red";
	else
		$tempColor ="blue";
	

	//How big thermo rate?
	if($currentTemp < -20)
		$faSymb = "full";
	elseif($currentTemp < -15)
		$faSymb = "three-quarters";
	elseif($currentTemp < -10)
		$faSymb = "half";
	elseif($currentTemp < 5)
		$faSymb = "quarter";
	elseif($currentTemp < 0)
		$faSymb = "empty";
	elseif($currentTemp < 10)
		$faSymb = "quarter";
	elseif($currentTemp < 20)
		$faSymb = "half";
	elseif($currentTemp < 25)
		$faSymb = "three-quarters";
	elseif($currentTemp < 30)
		$faSymb = "full";
	else
		$faSymb = "full blink";
	


  	// Trigger get_min_function
  	$windVal = get_max_wind("day", '', '', 0, 'H:i');
  	$maxWind = $windVal["maxWind"];
  	$maxWindTime = $windVal["maxWindTime"];
  	$maxDir = $windVal["maxDir"];
  	  	
  	
  	// Get sun hour info
  	
  	$sun_info = date_sun_info(strtotime("today"), 56.4892, 12.9316);
  	$sun_rise = date("H:i:s", $sun_info['sunrise']);
  	$sun_set = date("H:i:s", $sun_info['sunset']);
  	$diff = ($sun_info['sunset'] - $sun_info['sunrise']) / 60;
  	
	$sun_hours = hoursandmins($diff);



	//Get number of images in vaderbilder
    $imagecount = count(glob("../vaderbilder/history/*.jpg"));


function hoursandmins($time, $format = '%02d:%02d'){
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}
  	

?>



<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-140012722-1"></script>
	<script>
  		window.dataLayer = window.dataLayer || [];
  		function gtag(){dataLayer.push(arguments);}
  		gtag('js', new Date());
  		gtag('config', 'UA-140012722-1');
	</script>


  <title>Vädret i Mellbystrand</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="asset/css/main.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

  

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
	<a class="navbar-brand" href="https://www.mellbyvader.se/">Mellbyvader.se</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div id="navbarNavDropdown" class="navbar-collapse collapse">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" href="https://www.mellbyvader.se/mellbyvader-v2/">Hem</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="https://www.mellbyvader.se/mellbyvader-v2/grafer.php">Grafer</a>
			</li>
		</ul>
	</div>
</nav>

<div class="jumbotron">
  <div class="container text-left">
    <h1><b>Vädret i Mellbystrand</b></h1>      
    <p style="color:white;"><b>Senast uppdaterad: <?php echo $day . ", " . $time; ?></b></p>
  </div>
</div>
  
<div class="container-fluid bg-3 text-center">    
  <h3>Aktuellt väder</h3><br>  
    
  <div class="row">
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Temperatur</div>
		<div class="card-body">  				
			<td align=center> 
				<i class="fas fa-thermometer-<?php echo $faSymb; ?>" style="font-size:52px;color: <?php echo $tempColor ?> ;"></i> 
			</td>
			<td>	
				<b> &nbsp; <font size="5"> <?php echo $currentTemp . "&deg;C" ?> </font> </b>
			</td>
		</div> 
		<div class="card-footer">
			<table align=center>
				<tr>
					<td>
						<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> <?php echo $maxTemp . "&deg;C" . " kl. " . $maxTempTime ?>
					</td>
				</tr>
				<tr>
					<td>
						 <i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:blue;" ></i> <?php echo $minTemp . "&deg;C" . " kl. " . $minTempTime ?>
					</td>
				</tr>	
			</table>
		</div>
	  </div>
    </div>
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Vindhastighet</div>
  		
  		<div class="card-body">
  		  	<td> 
  				<i class="fas fa-long-arrow-alt-down" style="font-size:52px;color:#208000; transform:rotate(<?php echo str_replace(",",".", $windDir) ?>deg)"; ></i> 	
            	<b> &nbsp; <font size="5"> <?php echo $windSpeed; ?> </font> <font size="2"> m/s <?php if($windSpeed!=0){ echo " från " . wind_cardinals($windDir); } ?> </font> </b> 
            </td>
  		</div> 
  		
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>
						<td>
							 <i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> 
								<?php 
									echo $maxWind . " m/s ";  
									if($maxWind!=0){
										echo "från " . wind_cardinals($maxDir) . " kl. " . $maxWindTime;
									} 
								?>
						</td>
					</tr>         				
				 </table>
            </td>
        </div> 
        
	  </div>
    </div>
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Vindby</div>
  		<div class="card-body">
  			<td> 
  				<i class="fas fa-long-arrow-alt-down" style="font-size:52px;color:#006600; transform:rotate(<?php echo str_replace(",",".", $windGustDir) ?>deg)"; ></i> 	
                <b> &nbsp; <font size="5"> <?php echo $windGust ?> </font> <font size="2"> m/s  <?php if($windGust!=0){ echo " från " . wind_cardinals($windGustDir); } ?> </font> </b> 
            </td>	
        </div> 
        
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> 
							<?php 
								echo $maxWindGust  . " m/s "; 
								if($maxWindGust!=0){
									echo "från " . wind_cardinals($maxWindGustDir) . " kl. " . $maxWindGustTime; 
								}
							?>
						</td>
					</tr>       				
				 </table>
			</td>
  		</div> 
	  </div>
    </div>
    

  </div>
  
  
  
  <div class="row">
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Regn</div>
  		
  		<div class="card-body">
			<td> 
				<i class="fas fa-umbrella" style="font-size:52px;color:blue;"></i> 
				<b> &nbsp; <font size="4"><?php echo $rainSum . " mm, "; ?> </font> <font size="2"> <?php echo "Intensitet: " . $currentRain . " mm/h"; ?> </font> </b> 
			</td>
  		</div> 
  		
  		<div class="card-footer">
  			<td>
                <table align=center>
      				<td>
						<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> <?php echo $maxRain . " mm/h kl. " . $maxRainTime; ?>           
					</td>      				
         		</table>
       		</td>
  		</div> 
	  </div>
    </div>
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Lufttryck</div>
  		<div class="card-body">
  			<td> 
  					<i class="fas fa-clock " style="font-size:52px;color:#006666"></i> 
                	<b> &nbsp; <font size="5"> <?php echo $barometer ?> </font> <font size="2">mbar </font> </b> 
            </td>
  		</div> 
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> <?php echo $maxBarometer . " mbar kl. " . $maxBarometerTime; ?>  
						</td>
					</tr>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:blue;"></i> <?php echo $minBarometer . " mbar kl. " . $minBarometerTime; ?>  
						</td>
					</tr>
				 </table>
            </td>
  		</div> 
	  </div>
    </div>
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Solinstrålning</div>
  		<div class="card-body">
  			<td> 
  				<i class="far fa-sun" style="font-size:52px;color:#e6e600"></i> 
  				<b> &nbsp; <font size="5"> <?php echo $radiation ?> </font> <font size="2"> W/m² </font> </b>  
  			</td>
  		</div> 
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> <?php echo $maxRadiation . " W/m² kl. " . $maxRadiationTime; ?>
						</td>
					</tr>
					<tr>
						<td>
                            <?php echo "Totalt idag: " . $watt_sum . " kWh/m²"; ?>
						</td>
					</tr>
				 </table>
			</td>
  		</div> 
	  </div>
    </div>
    
  </div>

  <div class="row">
	<div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">UV-index</div>
  		
  		<div class="card-body">
  			<td> 
  				<i class="fas fa-sun" style="font-size:52px;color:#e65c00;"></i> 
                <b> &nbsp; <font size="5"> <?php echo $UV ?> </font>  <font size="2"> UV </font> </b>  
            </td>
        </div> 
                
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> <?php echo $maxUV . " UV kl. " . $maxUVTime; ?>  
						</td>
					</tr>
					<tr>
						<td>
						</td>
					</tr>
				 </table>
			</td>
  		</div> 
	  </div>
    </div>

    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Luftfuktighet</div>
  		
  		<div class="card-body">
  			<td> 
  				<i class="fas fa-tint" style="font-size:52px;color:#81D1FF;"></i> 
                <b> &nbsp; <font size="5"> <?php echo $outHumidity ?> </font>  <font size="2"> % </font> </b>  
            </td>
        </div> 
                
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i> <?php echo $maxHumidity . " % kl. " . $maxHumidityTime; ?>  
						</td>
					</tr>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:blue;"></i> <?php echo $minHumidity . " % kl. " . $minHumidityTime; ?>
						</td>
					</tr>
					
				 </table>
			</td>
  		</div> 
	  </div>
    </div>
    
    <div class="col-sm-4">
      <div class="card mt-4">
  		<div class="card-header">Dagens längd</div>
  		
  		<div class="card-body">
  			<td> 
  				<i class="fas fa-sun" style="font-size:52px;color:#ffff00;"></i> 
  				<b> &nbsp; <font size="5"> <?php echo $sun_hours ?> </font>  <font size="2"> hh:mm </font> </b>  
  			</td>
        </div> 
                
  		<div class="card-footer">
  			<td>
				<table align=center>
					<tr>					
						<td>
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:black;"></i> <?php echo "Soluppgång: " . $sun_rise; ?>  
						</td>
					</tr>
					<tr>
						<td>
							<i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:black;"></i> <?php echo "Solnedgång: " . $sun_set; ?>
						</td>
					</tr>
					
				 </table>
			</td>
  		</div> 
	  </div>
    </div>
    
  </div>

  <div class="row">
		<div class="col-sm-4">
            <br>
			<div class="card mt-4 h-90">
				<div class="card-header">Regnkarta</div>
				<div class="card-body"><iframe src="https://www.rainviewer.com/map.html?loc=56.6385,12.9141,8&oFa=0&oC=0&oU=0&oCS=0&oF=1&oAP=0&rmt=1&c=1&o=83&lm=0&th=0&sm=1&sn=1" width="100%" frameborder="0" style="border:0;height:81vh;" allowfullscreen></iframe></div>
                <div class="card-footer"><a href="https://www.rainviewer.com/"> https://www.rainviewer.com/ </a> </div>
            </div>
		</div>
		<div class="col-sm-8">
            <br>
			<div class="card mt-4 h-90">
				<div class="card-header">Webcam</div>
				<div class="card-body"><img id="webcam" src='../vaderbilder/aktuell.jpg' alt='Vädret i Mellbystrand Webcam' class='card-img-top'>
                    <input type="range" min="1" max=<?php echo $imagecount?> value="0" id="myRange">
                    <br>
                    <span id="demo"></span>
                </div>
				<div class="card-footer">Senast uppdaterad: <?php echo date('Y-m-d H:i' , filemtime('../vaderbilder/aktuell.jpg')); ?> </div>
			</div>
		</div>
  </div>
  <br>
  <br>
  
</div>
<br>


<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");

    var dt = new Date();
    dt.setDate(dt.getDate()-slider.value+1);
    var date = dt.getFullYear() + '-' + (((dt.getMonth() + 1) < 10) ? '0' : '') + (dt.getMonth() + 1) + '-' + ((dt.getDate() < 10) ? '0' : '') + dt.getDate();

    output.innerHTML = date;


    slider.oninput = function() {

        var dt = new Date();
        dt.setDate(dt.getDate()-slider.value+1);
        var date = dt.getFullYear() + '-' + (((dt.getMonth() + 1) < 10) ? '0' : '') + (dt.getMonth() + 1) + '-' + ((dt.getDate() < 10) ? '0' : '') + dt.getDate();

        output.innerHTML = date;//this.value;

        var img_src = "../vaderbilder/history/" + date + ".jpg";

        document.getElementById("webcam").src=img_src;

    }

</script>


<footer class="container-fluid text-center">
  <p>© 2019 Mellbyvader.se <br> <a href="http://weewx.com/">Powered by weeWX</a></p>
</footer>

</body>
</html>

<!-- Load all externals scripts to load graphs --!>
<script src="js/my_chart.js"></script>

