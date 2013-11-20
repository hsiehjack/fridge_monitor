<!DOCTYPE html>
<html>
<head>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages:['corechart', 'gauge']});
		google.setOnLoadCallback(drawChart);
                var jsonData = $.ajax({
                        url: "getJSON.php",
                        dataType:"json",
                        async: false
                        }).responseText;
		function drawChart() {
/*
			var jsonData = $.ajax({
				url: "getJSON.php",
				dataType:"json",
				async: false
			}).responseText;
*/
/* Static Gauge Datatable JSON
			var jsonData = {
				cols: [
					{id: 'Label', label: 'Label', type:'string'},
					{id: 'Value', label: 'Value', type: 'number'}
				],
				rows: [
					{c:[{v: 'Temperature'}, {v: 80}]},
					{c:[{v: 'Door Opened'}, {v: 0}]}
				]
			}
*/
/* Static Gauge DataTable
			var data = google.visualization.arrayToDataTable([
				['Label', 'Value'],
				['Temperature', 80],
			]);
*/

			var gaugeJsonData = $.ajax({
				url: "getJSON.php?style=gauge",
				dataType:"json",
				async: false
			}).responseText;
			var graphJsonData = $.ajax({
				url: "getJSON.php?style=graph",
				dataType:"json",
				async: false
			}).responseText;

			var gaugeData = new google.visualization.DataTable(gaugeJsonData);
			var graphData = new google.visualization.DataTable(graphJsonData);

			var gaugeOptions = {
				width: 200, height: 200,
				greenFrom: 32, greenTo: 40,
				yellowFrom:40, yellowTo: 50,
				redFrom: 50, redTo: 80,
				minorTicks: 10, majorTicks: [30, 40, 50, 60, 70, 80],
				min: 30, max: 80,
			};
			var graphOptions = {
				title: 'Temperature',
				vAxis: {
					gridlines:{count:5},
					minValue: 0,
					maxValue: 100
					} 
				/*hAxis: {
					gridlines:{count:3},
					minValue: new Date(2012, 1, 31),
					maxValue: new Date(2012, 9, 30),
					}*/	
			};

			var gauge = new google.visualization.Gauge(document.getElementById('gauge'));
			var graph = new google.visualization.LineChart(document.getElementById('graph'));

			gauge.draw(gaugeData, gaugeOptions);
			graph.draw(graphData, graphOptions);
		}
		// This will redraw the charts
		setInterval(drawChart, 5000);
	</script>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div id="container">
		<div id="gauge"></div>
		<div id="graph"></div>
	</div>
</body>
</html>
