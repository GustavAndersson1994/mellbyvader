
url_string = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/day.php?datetime=";
var lineGraph  = null;


function updateLabels(url_label){
	$(document).ready(function(){
	  $.ajax({
		url: url_label,
		method: "GET",
		dataType: 'json',
		success: function(data) {
		  console.log(data);
		  		  
		  document.getElementById("p1").innerHTML = data["outTemp"][0];
		  document.getElementById("p2").innerHTML = data["outTemp"][1];	
		  
		  document.getElementById("p4").innerHTML = data["rain"];	
		  
		  document.getElementById("p7").innerHTML = data["wind"];	

		  
		  document.getElementById("p10").innerHTML = data["barometer"][0];
		  document.getElementById("p11").innerHTML = data["barometer"][1];	
		  
		  document.getElementById("p13").innerHTML = data["radiation"][0];
		  
		  document.getElementById("p16").innerHTML = data["UV"][0];
		  
		  document.getElementById("p19").innerHTML = data["outHumidity"][0];
		  document.getElementById("p20").innerHTML = data["outHumidity"][1];	
		  
		  document.getElementById("p22").innerHTML = data["wind_gust"];


        },
		
		error: function(data) {
		  console.log(data);
		}
	  });
	});
	
}


function getRainData(url_rain){
	$(document).ready(function(){
	  $.ajax({
		url: url_rain,
		method: "GET",
		dataType: 'json',
		success: function(data) {
		  console.log(data);
		  	
		  	var date = [];
		  	var rain = [];
		  	
		  	for(var i in data) {
				date.push(data[i].date);
				rain.push(data[i].rain);
		 	}
		 	
		 	
		 	
		  // Rain
		  var ctx2 = $("#rainChart");
		  var lineGraph = new Chart(ctx2, {
			type: 'bar',
			data: {
			labels: date,
			datasets : [
			  {
				label: 'Regn',
				backgroundColor: 'rgba(0, 0, 255, 0.75)',
				borderColor: 'rgba(0, 0, 255, 0.75)', 
				hoverBackgroundColor: 'rgba(0, 0, 255, 0.75)',
				hoverBorderColor: 'rgba(0, 0, 255, 0.75)',
				data: rain,
			  }
			]
		  },
			options: {  
				responsive: true,
				maintainAspectRatio: false
			}
		  });
		 	
		 	

		  
		  },
		
		error: function(data) {
		  console.log(data);
		}
	  });
	});
	
}



function getRadiationData(url_radiation){
    $(document).ready(function(){
        $.ajax({
            url: url_radiation,
            method: "GET",
            dataType: 'json',
            success: function(data) {
                console.log(data);

                var date = [];
                var radiation = [];

                for(var i in data) {
                    date.push(data[i].date);
                    radiation.push(data[i].radiation);
                }

                var watt_sum = radiation.reduce(sum_arr);
                watt_sum = watt_sum.toFixed(2);
                document.getElementById("p23").innerHTML = "Totalt denna period: " + watt_sum +  " kWh/m²";


                // Radiation
                var ctx10 = $("#radiationSumChart");
                var lineGraph = new Chart(ctx10, {
                    type: 'bar',
                    data: {
                        labels: date,
                        datasets : [
                            {
                                label: 'Effekt',
                                backgroundColor: 'rgba(255, 153, 0, 0.75)',
                                borderColor: 'rgba(255, 153, 0, 0.75)',
                                hoverBackgroundColor: 'rgba(255, 153, 0, 0.75)',
                                hoverBorderColor: 'rgba(255, 153, 0, 0.75)',
                                data: radiation,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

            },

            error: function(data) {
                console.log(data);
            }
        });
    });

}



function updateChart() {

	if(!isValidDate(document.getElementById("datetime").value)){
		alert("Fel format på datum. Använd YYYY-mm-dd");
	}
	document.getElementById("p3").innerHTML = '<canvas id="tempChart" style="height:300px;"></canvas>';
	document.getElementById("p6").innerHTML = '<canvas id="rainChart" style="height:300px;"></canvas>';
	document.getElementById("p9").innerHTML = '<canvas id="windChart" style="height:300px;"></canvas>';
	document.getElementById("p12").innerHTML = '<canvas id="barChart" style="height:300px;"></canvas>';
	document.getElementById("p15").innerHTML = '<canvas id="radiationChart" style="height:300px;"></canvas>';
	document.getElementById("p18").innerHTML = '<canvas id="UVChart" style="height:300px;"></canvas>';
	document.getElementById("p21").innerHTML = '<canvas id="humChart" style="height:300px;"></canvas>';
	document.getElementById("p24").innerHTML = '<canvas id="windGustChart" style="height:300px;"></canvas>';
    document.getElementById("p25").innerHTML = '<canvas id="radiationSumChart" style="height:300px;"></canvas>';


    var date_str = document.getElementById("datetime").value;
	
	
	
	
	if($("#field_6 option:selected").text() == "Dag"){
		url_string = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/weather_data.php?interval=day&datetime=";
		url_label = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/labels.php?interval=day&datetime=";
		url_rain = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/rain_data.php?interval=day&datetime=";
        url_radiation = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/radiation_data.php?interval=day&datetime=";
    }else if($("#field_6 option:selected").text() == "Vecka"){
		url_string = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/weather_data.php?interval=week&datetime=";
		url_label = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/labels.php?interval=week&datetime=";
		url_rain = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/rain_data.php?interval=week&datetime=";
        url_radiation = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/radiation_data.php?interval=week&datetime=";
    }else if($("#field_6 option:selected").text() == "Månad"){
		url_string = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/weather_data.php?interval=month&datetime=";
		url_label = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/labels.php?interval=month&datetime=";
		url_rain = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/rain_data.php?interval=month&datetime=";
        url_radiation = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/radiation_data.php?interval=month&datetime=";
    }else if($("#field_6 option:selected").text() == "År"){
		url_string = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/weather_data.php?interval=year&datetime=";
		url_label = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/labels.php?interval=year&datetime=";
		url_rain = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/rain_data.php?interval=year&datetime=";
        url_radiation = "https://www.mellbyvader.se/mellbyvader-v2/src/json_data/radiation_data.php?interval=year&datetime=";
    }
	
	
	url_string = url_string + date_str;
	url_label = url_label + date_str;
	url_rain = url_rain + date_str;
    url_radiation = url_radiation + date_str;



    updateLabels(url_label);

	
	$(document).ready(function(){
	  $.ajax({
		url: url_string,
		method: "GET",
		dataType: 'json',
		success: function(data) {
		  console.log(data);

		   	var temp = [];
			var time = [];
		  	var rain = [];
		   	var barometer = [];
			var radiation = [];
			var UV = [];
			var humidity = [];
			var windSpeed = [];
			var windDir = [];
			var windGust = [];
			var windGustDir = [];
		
		  for(var i in data) {
			time.push(data[i].dateTime);
			temp.push(data[i].outTemp);
			rain.push(data[i].rain);
			barometer.push(data[i].barometer);
			radiation.push(data[i].radiation);
			UV.push(data[i].UV);
			humidity.push(data[i].outHumidity);
			windSpeed.push(data[i].windSpeed);
			windDir.push(data[i].windDir);
			windGust.push(data[i].windGust);
			windGustDir.push(data[i].windGustDir);
		  }

	  
	  
		  // Temp
		  var ctx = $("#tempChart");
		  var lineGraph = new Chart(ctx, {
			type: 'line',
			data: {
			labels: time,
			datasets : [
			  {
				label: 'Temperatur',
				backgroundColor: 'rgba(255, 0, 0, 0.75)',
				borderColor: 'rgba(255, 0, 0, 0.75)',
				hoverBackgroundColor: 'rgba(255, 0, 0, 0.75)',
				hoverBorderColor: 'rgba(255, 0, 0, 0.75)',
				fill: false,
				pointRadius: 1,
				pointHoverRadius: 1,
				borderWidth: 2,
				data: temp,
			  }
			]
		  },
			options: {  
				responsive: true,
				maintainAspectRatio: false
			}
		  });
		  
		  
		  if($("#field_6 option:selected").text() == "Dag"){
		  
			  // Rain
			  var ctx2 = $("#rainChart");
			  var lineGraph = new Chart(ctx2, {
				type: 'bar',
				data: {
				labels: time,
				datasets : [
				  {
					label: 'Regn',
					backgroundColor: 'rgba(0, 0, 255, 0.75)',
					borderColor: 'rgba(0, 0, 255, 0.75)', 
					hoverBackgroundColor: 'rgba(0, 0, 255, 0.75)',
					hoverBorderColor: 'rgba(0, 0, 255, 0.75)',
					data: rain,
				  }
				]
			  },
				options: {  
					responsive: true,
					maintainAspectRatio: false
				}
			  });
		  }else{
		  		getRainData(url_rain);
		  }
		  
		  
		  
		  // Wind
		  var ctx7 = $("#windChart");
			new Chart(ctx7, {
			  type: 'line',
			  data: {
				labels: time,
				datasets: [{
				  label: 'Vindhastighet',
				  yAxisID: 'A',
				  data: windSpeed,
				  backgroundColor: 'rgba(32, 128, 0, 0.75)',
				  borderColor: 'rgba(32, 128, 0, 0.75)', 
				  hoverBackgroundColor: 'rgba(32, 128, 0, 0.75)',
			      hoverBorderColor: 'rgba(32, 128, 0, 0.75)',
				  fill: false,
				  pointRadius: 1,
				  pointHoverRadius: 1,
				  borderWidth: 1.5,
				}, {
				  label: 'Vindriktning',
				  yAxisID: 'B',
				  data: windDir,
				  backgroundColor: 'rgba(51, 153, 255, 0.75)',
				  borderColor: 'rgba(51, 153, 255, 0.75)',
				  hoverBackgroundColor: 'rgba(51, 153, 255, 0.75)',
				  hoverBorderColor: 'rgba(51, 153, 255, 0.75)',
				  fill: false,
				  pointRadius: 1,
				  pointHoverRadius: 1,
				  borderWidth: 1.5,
				  showLine: false,
                }]
			  },
			  options: {
			  	responsive: true,
				maintainAspectRatio: false,
				scales: {
				  yAxes: [{
					id: 'A',
					type: 'linear',
					position: 'left',
				  }, {
					id: 'B',
					type: 'linear',
					position: 'right',
                    display: true,
                    gridLines: {
                        display:false
                    },
                    ticks: {
                        autoSkip: false,
                        beginAtZero: true,
                        min: 0,
                        max: 360,
                        stepSize: 90,
                        callback: function(value, index, values) {
                        	if(index == 0 || index == 4){
								return "N";
							}else if(index == 1) {
                        		return "V";
							} else if(index == 2) {
                                return "S";
                            }else if(index == 3) {
                                return "O";
                            }
						}
                    }
				  }]
				}
			  }
			});
			
			
			
		  // WindGust
		  var ctx8 = $("#windGustChart");
			new Chart(ctx8, {
			  type: 'line',
			  data: {
				labels: time,
				datasets: [{
				  label: 'Vindby',
				  yAxisID: 'A',
				  data: windGust,
				  backgroundColor: 'rgba(32, 128, 0, 0.75)',
				  borderColor: 'rgba(32, 128, 0, 0.75)',
				  hoverBackgroundColor: 'rgba(32, 128, 0, 0.75)',
			      hoverBorderColor: 'rgba(32, 128, 0, 0.75)',
				  fill: false,
				  pointRadius: 1,
				  pointHoverRadius: 1,
				  borderWidth: 1.5,
				}, {
				  label: 'Vindriktning',
				  yAxisID: 'B',
				  data: windGustDir,
                  backgroundColor: 'rgba(51, 153, 255, 0.75)',
                  borderColor: 'rgba(51, 153, 255, 0.75)',
                  hoverBackgroundColor: 'rgba(51, 153, 255, 0.75)',
                  hoverBorderColor: 'rgba(51, 153, 255, 0.75)',
				  fill: false,
				  pointRadius: 1,
				  pointHoverRadius: 1,
				  borderWidth: 1.5,
                  showLine: false
                }]
			  },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            id: 'A',
                            type: 'linear',
                            position: 'left',
                        }, {
                            id: 'B',
                            type: 'linear',
                            position: 'right',
                            display: true,
                            gridLines: {
                                display:false
                            },
                            ticks: {
                                autoSkip: false,
                                beginAtZero: true,
                                min: 0,
                                max: 360,
                                stepSize: 90,
                                callback: function(value, index, values) {
                                    if(index == 0 || index == 4){
                                        return "N";
                                    }else if(index == 1) {
                                        return "V";
                                    } else if(index == 2) {
                                        return "S";
                                    }else if(index == 3) {
                                        return "O";
                                    }
                                }
                            }
                        }]
                    }
                }
            });
		  

		  // Pressure
		  var ctx3 = $("#barChart");
		  var lineGraph = new Chart(ctx3, {
			type: 'line',
			data: {
			labels: time,
			datasets : [
			  {
				label: 'Lufttryck',
				backgroundColor: 'rgba(0, 102, 102, 0.75)',
				borderColor: 'rgba(0, 102, 102, 0.75)',
				hoverBackgroundColor: 'rgba(0, 102, 102, 0.75)',
				hoverBorderColor: 'rgba(0, 102, 102, 0.75)',
				fill: false,
				pointRadius: 1,
				pointHoverRadius: 1,
				borderWidth: 2,
				data: barometer,
			  }
			]
		  },
			options: {  
				responsive: true,
				maintainAspectRatio: false
			}
		  });
		  
		  
		  
		  // Radiation
		  var ctx4 = $("#radiationChart");
		  var lineGraph = new Chart(ctx4, {
			type: 'line',
			data: {
			labels: time,
			datasets : [
			  {
				label: 'Solinstrålning',
				backgroundColor: 'rgba(230, 230, 0, 0.75)',
				borderColor: 'rgba(230, 230, 0, 0.75)', 
				hoverBackgroundColor: 'rgba(230, 230, 0, 0.75)',
				hoverBorderColor: 'rgba(230, 230, 0, 0.75)',
				fill: true,
				pointRadius: 1,
				pointHoverRadius: 1,
				borderWidth: 2,
				data: radiation,
			  }
			]
		  },
			options: {  
				responsive: true,
				maintainAspectRatio: false
			}
		  });




		// Radiation sum
		if($("#field_6 option:selected").text() == "Dag"){

            var watt_sum = radiation.reduce(sum_arr)*600/3600000;
            watt_sum = watt_sum.toFixed(2);
            var today = document.getElementById('datetime').value;
            document.getElementById("p23").innerHTML = "Totalt denna period: " + watt_sum +  " kWh/m²";


            var ctx7 = $("#radiationSumChart");
			var lineGraph = new Chart(ctx7, {
				type: 'bar',
				data: {
					labels: [today],
					datasets : [
						{
							label: 'Solenergi',
							backgroundColor: 'rgba(255, 153, 0, 0.75)',
							borderColor: 'rgba(255, 153, 0, 0.75)',
							hoverBackgroundColor: 'rgba(255, 153, 0, 0.75)',
							hoverBorderColor: 'rgba(255, 153, 0, 0.75)',
							data: [watt_sum],
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
				}
			});
		}else{
			getRadiationData(url_radiation);
		}



		  
		  // UV
		  var ctx5 = $("#UVChart");
		  var lineGraph = new Chart(ctx5, {
			type: 'line',
			data: {
			labels: time,
			datasets : [
			  {
				label: 'UV-index',
				backgroundColor: 'rgba(230, 92, 0, 0.75)',
				borderColor: 'rgba(230, 92, 0, 0.75)', 
				hoverBackgroundColor: 'rgba(230, 92, 0, 0.75)',
				hoverBorderColor: 'rgba(230, 92, 0, 0.75)',
				fill: false,
				pointRadius: 1,
				pointHoverRadius: 1,
				borderWidth: 2,
				data: UV,
			  }
			]
		  },
			options: {  
				responsive: true,
				maintainAspectRatio: false
			}
		  });
		  
		  
		  // Humidity
		  var ctx6 = $("#humChart");
		  var lineGraph = new Chart(ctx6, {
			type: 'line',
			data: {
			labels: time,
			datasets : [
			  {
				label: 'Luftfuktighet',
				backgroundColor: 'rgba(129, 209, 255, 0.75)',
				borderColor: 'rgba(129, 209, 255, 0.75)', 
				hoverBackgroundColor: 'rgba(129, 209, 255, 0.75)',
				hoverBorderColor: 'rgba(129, 209, 255, 0.75)',
				fill: false,
				pointRadius: 1,
				pointHoverRadius: 1,
				borderWidth: 2,
				data: humidity,
			  }
			]
		  },
			options: {  
				responsive: true,
				maintainAspectRatio: false
			}
		  });
		  
		    
		  
		},
		
		error: function(data) {
		  console.log(data);
		}
	  });
	});
	
 }

 $('#field_6').add('#datetime').on('change', updateChart);
 
  
 
function updateDate() {
	if(!isValidDate(document.getElementById("datetime").value)){
		alert("Fel format på datum. Använd YYYY-mm-dd");
	}else{
		updateChart();
	}
}


function isValidDate(date_string) {
	var d = new Date(date_string);
	return d instanceof Date && !isNaN(d);
}


function sum_arr(total, num) {
    return total + num;
}
	
	 
 
 
