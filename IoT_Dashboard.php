<?php
// Locate the Sensor Database file
$dbh = new PDO('sqlite:C:/Desktop/SENSORDB.db');
$query=$dbh->prepare("SELECT p, q, r, Temp, phi, theta, psi, Ax, Ay, Az, Mx, My, Mz from Sensor order by _rowid_ desc limit 1");
$query->execute();
$result=$query->fetch();

$json = array( (floatval($result[0]))=>0, (floatval($result[1])),(floatval($result[2])),(floatval($result[3])),(floatval($result[4])),(floatval($result[5])),(floatval($result[6])),(floatval($result[7])),(floatval($result[8])),(floatval($result[9])),(floatval($result[10])),(floatval($result[11])),(floatval($result[12])) );
$data = json_encode($json);
// Give The Json file location for sensor Data
$stringnew = file_put_contents("C:\Desktop\SensorJsonData.json", $data);
//echo $string;
//echo strlen($data);
$serialized_data = serialize($data);
$size = strlen($serialized_data);
echo "Data Packet length in byte : $size <br />";
print("Packet length in kilo bit <br />");
print($size * 8 / 1000);

?>
<br></br>
<br></br>
<br></br>
<title>Sensor Status</title>
  
<div style="color:#0000FF;margin-top:-80px;">
  <h1>End to End IOT Research Lab</h3>
  <p>The IOT solution contains different sensors which measures different parameters like Temperature, Pressure, Humadity, Acceleration, Speed, GPS, Gyro, Magnetic field and Airspeed. 
  The sensors streams data and streaming Data is stored into SQLite DB. The Filtering and data processing is done at Gateway Node. 
  The data sensor visulalisation is done by using different interactive Guages, charts and linear graph on localserver by using Apache, javascript and Php. 
  The Sensor Data is also send to Cloud side and can be visualise effectively by using different javascript pluggins running at dweet.io and Freeboard ”</p>
</div>
<div id="chart6" style="float:left;margin-top:20px;">
<?php echo "Gravitational, X Value-> :$result[0] <br />"; echo "Gravitational, Y Value->:$result[1] <br />";
echo "Gravitational, Z Value-> :$result[2]<br />";
echo "Temperature Value:$result[3] <br />";
echo "GPS Data : $result[4] <br />";
echo "Pressure Data : $result[5] <br />";
echo "Humidity Data : $result[6] <br />";
echo "Acceleration, X Value:$result[7] <br />";
echo "Acceleration, Y Value:$result[8] <br />";
echo "Acceleration, Z Value:$result[9] <br />";
echo "Magnetic Field, X -->Value:$result[10]<br />";
echo "Magnetic Field, Y -->Value:$result[11]<br />";
echo "Magnetic Field, Z -->Value:$result[12]<br />";
?>
</div>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta name="viewport" content="mpimum-scale=1.6,width=320,user-scalable=false" />
  <meta http-equiv="refresh" content="5";url="http://localhost:8080/addemp/gaugematdweet.php">
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  

  
<?php

try
{
$dbh = new PDO('sqlite:D:/MatlabStup/bin/SENSORDB.db');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// query result will look like the following:
// 11/17/12 06:47:24 Ay=092% p=128
$query=$dbh->prepare("SELECT p, q, r, Temp from Sensor order by _rowid_ desc limit 1");
$query->execute();
$result=$query->fetch();

$chart_p_data = $result[0];
//$chart_Ay_data = substr($result[2], 0, -1); // strip off the last '%' character
$chart_q_data = $result[1];
//$last_update = $result[0] . " @ " . $result[1];
$now = new DateTime();

$last_update = $now->format('Y-m-d H:i:s') . $now->getTimestamp();

$query=$dbh->prepare("SELECT p, q, r, Temp from Sensor order by _rowid_ desc limit 1");
$query->execute();
$result=$query->fetch();
$chart_r_data = $result[2];
$chart_Temp_data = $result[3];

$dbh = null;

}
catch(PDOException $e)
{
print 'Exception: ' .$e->getMessage();
}

?>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
// pass PHP variable declared above to JavaScript variable
var data1 = <?php echo json_encode($data, JSON_FORCE_OBJECT); ?>;
var jsonstring = JSON.parse(data1);


</script>
    
	<dweet io Start Code>
	<script type="text/javascript" script src="//dweet.io/client/dweet.io.min.js"></script>
	<script type="text/javascript">
	 dweetio.dweet_for("jydweet", jsonstring, function(err, dweet){
    console.log(dweet.thing); // "my-thing"
    console.log(dweet.content); // The content of the dweet
    console.log(dweet.created); // The create date of the dweet

});
   </script>
   
   <Dweet io end code>
   
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['gauge']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
		var p_data = google.visualization.arrayToDataTable([
			['Label', 'Value'],
  			['p', <?php echo $chart_p_data; ?>]]);

		var q_data = google.visualization.arrayToDataTable([
			['Label', 'Value'],
  			['q', <?php echo $chart_q_data; ?>]]);

		var r_data = google.visualization.arrayToDataTable([
			['Label', 'Value'],
  			['r', <?php echo $chart_r_data; ?>]]);

		var Temp_data = google.visualization.arrayToDataTable([
			['Label', 'Value'],
  			['Temp', <?php echo $chart_Temp_data; ?>]]);

        var p_Chart_Options = {
          min: -5, max: 5,
          greenFrom: 0, greenTo: 5,
          redFrom: -5, redTo: -3,
          yellowFrom:-3, yellowTo: 0,
          minorTicks: 5,
        };
        		    
        var q_Chart_Options = {
          min: -2, max: 2,
          greenFrom: 0, greenTo: 2,
          redFrom: -2, redTo: -0.5,
          yellowFrom:-0.5, yellowTo: 0,
          minorTicks: 5,
        };
		
		var r_Chart_Options = {
          min: -2, max: 1,
          greenFrom: 110, greenTo: 140,
          redFrom: 50, redTo: 80,
          yellowFrom:80, yellowTo: 100,
          minorTicks: 5,
        };
		
		var Temp_Chart_Options = {
          min: 0, max: 100,
          greenFrom: 110, greenTo: 140,
          redFrom: 50, redTo: 80,
          yellowFrom:80, yellowTo: 100,
          minorTicks: 5,
        };
        		    
        // Create and draw the visualization.
        var p_chart = new google.visualization.Gauge(document.getElementById('chart1'));
        p_chart.draw(p_data, p_Chart_Options);

        var q_chart = new google.visualization.Gauge(document.getElementById('chart2'));
        q_chart.draw(q_data, q_Chart_Options);

        var r_chart = new google.visualization.Gauge(document.getElementById('chart3'));
        r_chart.draw(r_data, r_Chart_Options);

        var Temp_chart = new google.visualization.Gauge(document.getElementById('chart4'));
        Temp_chart.draw(Temp_data, Temp_Chart_Options);
      }

      google.setOnLoadCallback(drawVisualization);
    </script>

  </head>
  <body style="font-family: Arial;border: 0 none; background-color: lightgray">
  <div id = "chartnew" style = "margin-top:-10px" "margin-left:800px;"> <?php echo "Sensor data Updated: $last_update"; ?> </ br> </ br> </ br></div>
  <div id="containerDiv" style="width: 300px; height: 400px; float: right">
    <div id="chart1" style="float:left; width: 120px; height: 120px;"></div>
    <div id="chart2" style="float:left; width: 120px; height: 120px;"></div>
    <div id="chart3" style="float:left; width: 120px; height: 120px;"></div>
    <div id="chart4" style="float:left; width: 120px; height: 120px;"></div>
	<button id="button">Click Me For Sensor Recent Value</button>
   <canvas id="myCanvas" style="float:right";width="800"height="800"></canvas>
  </div>
  </body>
  <body style="font-family: Arial;border: 0 none; background-color: blue">
  
  </body>
</html>
​
<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
   
    // Load the Visualization API.
    google.load('visualization', '1', {'packages':['corechart']});
     
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
     
    function drawChart() {
      var jsonData = $.ajax({
          url: "getData.php",
          dataType:"json",
          async: false
          }).responseText;
         
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, {width: 800, height: 400});
    }

    </script>
	
  </head>

  <body>
    <!--Div that will hold the column chart-->
    <div id="chart_div"></div>
  </body>
</html>
  <br></br>
  <br></br>
  <br></br>
  <br></br>
  <br></br>
  <br></br>
  <br></br>
  <br></br>
  
  <!DOCTYPE HTML>
<html>

<head>
	<script type="text/javascript">
	window.onload = function () {
        var p1 = [<?php echo $data ?>];
		var dps = p1; // dataPoints
        //var jsonstring = JSON.parse(dps);
		
		//var ar = <?php echo json_encode($data) ?>;
		//var ar1 = <?php echo json_encode($data); ?>;
		//var q1 = JSON.parse(p1);
		var chart = new CanvasJS.Chart("chartContainer",{
			title :{
				text: "Live Temperature Data Under Developement"
			},			
			data: [{
				type: "line",
				dataPoints: p1
			}]
		});

		var xVal = 0;
		var yVal = 50;	
		var updateInterval = 80;
		var dataLength = 200; // number of dataPoints visible at any point

		var updateChart = function (count) {
			count = count || 1;
			// count is number of times loop runs to generate random dataPoints.
			
			for (var j = 0; j < count; j++) {	
				yVal = yVal +  Math.round(5 + Math.random() *(-5-5));
				dps.push({
					x: xVal,
					y: yVal
				});
				xVal++;
			};
			if (dps.length > dataLength)
			{
				dps.shift();				
			}
			
			chart.render();		

		};

		// generates first set of dataPoints
		updateChart(dataLength); 

		// update chart after specified time. 
		setInterval(function(){updateChart()}, updateInterval); 

	}
	</script>
	<script type="text/javascript" src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body>
	<div id="chartContainer" style="height: 200px; width:100%; margin-top:200px;">
	</div>
</body>
</html>
<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
      
    function drawChart() {
      var jsonData = $.ajax({
          url: "getData.php",
          dataType: "json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, {width: 400, height: 240});
    }

    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
