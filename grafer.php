<!-- Created by Gustav Andersson 2019-05-04 --!>
<?php

    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');

	include 'src/dbData.php';
	include 'src/functions.php';
	
	
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
	
	<script>
		$(document).ready(function() {
			$("#field_6").change(function() {
				if($(this).val() == "day"){
					var period_label = "Dag";
					var max_temp = "<?php echo $maxTemp . "&deg;C" . " kl. " . $maxTempTime ?>";
					var min_temp = "<?php echo $minTemp . "&deg;C" . " kl. " . $minTempTime ?>";
				}else if($(this).val() == "week"){
					var period_label = "Vecka";
					var max_temp = "<?php get_min_max("outTemp", "week", "max", "°C") ?>";
					var min_temp = "<?php get_min_max("outTemp", "week", "min", "°C") ?>";
				}else if($(this).val() == "month"){
					var period_label = "Månad";
					var max_temp = "<?php get_min_max("outTemp", "month", "max", "°C") ?>";
					var min_temp = "<?php get_min_max("outTemp", "month", "min", "°C") ?>";
				}else if($(this).val() == "year"){
					var period_label = "År";
					var max_temp = "<?php get_min_max("outTemp", "year", "max", "°C") ?>";
					var min_temp = "<?php get_min_max("outTemp", "year", "min", "°C") ?>";
				}
				
				document.getElementById("period").innerHTML = period_label;	
				document.getElementById("period2").innerHTML = period_label;							
				document.getElementById("period3").innerHTML = period_label;	
				document.getElementById("period4").innerHTML = period_label;	
				document.getElementById("period5").innerHTML = period_label;	
				document.getElementById("period6").innerHTML = period_label;
				document.getElementById("period7").innerHTML = period_label;	
				document.getElementById("period8").innerHTML = period_label;
                document.getElementById("period9").innerHTML = period_label;


                document.getElementById("p1").innerHTML = max_temp;
				document.getElementById("p2").innerHTML = min_temp;	

			}).change();
		});
	</script>

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
    <h3>Grafer</h3><br>
    <div class="card h-100 mt-4">
        <div class="card-header"><b>Grafer</b></div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8 col-xs-6 mt-2">
                    <div class="dropdown">
                        <select class="text_select" id="field_6" name="field_6">    
							<option value="day">Dag</option>  
							<option value="week">Vecka</option>  
							<option value="month">Månad</option>  
							<option value="year">År</option>  
						</select>
                    </div>
                </div>
                <br>
                <div class="col-md-4 col-xs-6 datetime_container mt-2">
                        <input type="text" class="form-control" value="<?php echo $day; ?>" id="datetime" size="10" />
                </div>
            </div>

		  <div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Temperatur [°C]</div>
					<span id="p3"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min temperatur</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
								<i class="fas fa-thermometer-<?php echo $faSymb; ?>" style="font-size:44px;color: <?php echo $tempColor ?> ;"></i> 
							</td>
							<td>	
								<b> &nbsp; <font size="5"> <?php echo $currentTemp . "&deg;C" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p1"> </span>
						</div>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:blue;" ></i>&nbsp; Min: &nbsp;
							<span id="p2"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>
	    

		<div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Regn [mm]</div>
					<span id="p6"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min regn</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="fas fa-umbrella" style="font-size:48px;color:blue;"></i> 
							</td>
							<td>	
								<b> &nbsp; <font size="5"> <?php echo $currentRain . " mm" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period2"></span></h5>
						<div class="row justify-content-center">
							<span id="p4"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>
	    
	    
	    <div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Vindhastighet/vindriktning [m/s]</div>
					<span id="p9"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min vindhastighet [m/s]</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="fas fa-long-arrow-alt-down" style="font-size:48px;color:#208000; transform:rotate(<?php echo str_replace(",",".", $windDir) ?>deg)"; ></i> 	
							</td>
							<td>	
								<b> &nbsp; &nbsp; <font size="5"> <?php echo $windSpeed . " m/s" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period3"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p7"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>
	    
	    
	    <div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Vindby/vindriktning [m/s]</div>
					<span id="p24"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min vindby [m/s]</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="fas fa-long-arrow-alt-down" style="font-size:48px;color:#208000; transform:rotate(<?php echo str_replace(",",".", $windGustDir) ?>deg)"; ></i> 	
							</td>
							<td>	
								<b> &nbsp; &nbsp; <font size="5"> <?php echo $windGust . " m/s" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period4"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p22"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>
	    
	    

		<div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Lufttryck [mbar]</div>
					<span id="p12"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min lufttryck</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="fas fa-clock " style="font-size:48px;color:#006666"></i> 
							</td>
							<td>	
								<b> &nbsp; <font size="5"> <?php echo $barometer . " mbar" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period5"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p10"> </span>
						</div>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:blue;" ></i>&nbsp; Min: &nbsp;
							<span id="p11"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>



		<div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Solinstrålning [W/m<sup>2</sup>]</div>
					<span id="p15"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min solstrålning</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="far fa-sun" style="font-size:48px;color:#e6e600"></i> 
							</td>
							<td>	
								<b> &nbsp; <font size="5"> <?php echo $radiation . " W/m<sup>2</sup>" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period6"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p13"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>


        <div class="row">
            <div class="col-md-8 col-xs-6 mt-4">
                <div class="card h-100 mt-4">
                    <div class="card-header">Solenergi [kWh/m<sup>2</sup>]</div>
                    <span id="p25"> </span>
                </div>
            </div>

            <div class="col-md-4 col-xs-6 mt-4">
                <div class="card h-100 mt-4">
                    <div class="card-header">Solenergi idag [kWh/m<sup>2</sup>] </div>
                    <div class="card-body">
                        <h5>Total energi idag</h5>
                        <div class="row justify-content-center">
                            <td align=center>
                                <i class="fas fa-solar-panel" style="font-size:48px;color:#ff9900"></i>
                            </td>
                            <td>
                                <b> &nbsp; <font size="5"> <?php echo $watt_sum . " kWh/m<sup>2</sup>" ?> </font> </b>
                            </td>
                        </div>
                        <br>
                        <h5><span id="period9"></span></h5>
                        <div class="row justify-content-center">
                            <span id="p23"> </span>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <br>
	    
	    
	    
	    <div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">UV-index</div>
					<span id="p18"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min UV-index</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="fas fa-sun" style="font-size:48px;color:#e65c00;"></i> 
							</td>
							<td>	
								<b> &nbsp; <font size="5"> <?php echo $UV . " " ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period7"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p16"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>
	    
	    
	    
	    <div class="row">
		     <div class="col-md-8 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Luftfuktighet [%]</div>
					<span id="p21"> </span> 
				</div>
			 </div>

			 <div class="col-md-4 col-xs-6 mt-4">
				<div class="card h-100 mt-4">
					<div class="card-header">Max/min luftfuktighet</div>
					<div class="card-body">
						<h5>Just nu</h5>
						<div class="row justify-content-center">
							<td align=center> 
  								<i class="fas fa-tint" style="font-size:48px;color:#81D1FF;"></i> 
							</td>
							<td>	
								<b> &nbsp; <font size="5"> <?php echo $outHumidity . " %" ?> </font> </b>
							</td>
						</div>
						<br>
    					<h5><span id="period8"></span></h5>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-up" style="font-size:18px;color:red;"></i>&nbsp; Max: &nbsp;
							<span id="p19"> </span>
						</div>
						<div class="row justify-content-center">
							<i class="fas fa-long-arrow-alt-down" style="font-size:18px;color:blue;" ></i>&nbsp; Min: &nbsp;
							<span id="p20"> </span>
						</div>
						<br>							
					</div>
				</div>
			 </div>
          </div>
	    <br>


	    
    </div>
</div>


<br>


<footer class="container-fluid text-center">
  <p>© 2019 Mellbyvader.se <br> <a href="http://weewx.com/">Powered by weeWX</a></p>
</footer>

</body>
</html>


<!-- Load all externals scripts to load graphs --!>
<script src="js/my_chart.js"></script>



