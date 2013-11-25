<!DOCTYPE html>
<html>
<head>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages:['annotatedtimeline', 'corechart', 'gauge']});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
/*
			var jsonData = $.ajax({
				url: "getJSON.php",
				dataType:"json",
				async: false
			}).responseText;
// Static Gauge Datatable JSON
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
// Static Gauge DataTable
			var data = google.visualization.arrayToDataTable([
				['Label', 'Value'],
				['Temperature', 80],
			]);
*/
			gauge();
			graph();
			timeline();
		}
		function gauge() {
			var jsonData = $.ajax({
				url: "getJSON.php?style=gauge",
				dataType:"json",
				async: false
			}).responseText;
			var data = new google.visualization.DataTable(jsonData);
			var options = {
				width: 200, height: 200,
				greenFrom: 32, greenTo: 40,
				yellowFrom:40, yellowTo: 50,
				redFrom: 50, redTo: 80,
				minorTicks: 10, majorTicks: [30, 40, 50, 60, 70, 80],
				min: 30, max: 80,
			};
			var gauge = new google.visualization.Gauge(document.getElementById('gauge'));
			gauge.draw(data, options);
		}
		function graph() {
			var jsonData = $.ajax({
				url: "getJSON.php?style=graph",
				dataType:"json",
				async: false
			}).responseText;
			var data = new google.visualization.DataTable(jsonData);
			var options = {
				title: 'Last hour Temperature',
				vAxis: {
					gridlines:{count:10},
					minValue: 30,
					maxValue: 50
					} 
				/*hAxis: {
					gridlines:{count:3},
					minValue: new Date(2012, 1, 31),
					maxValue: new Date(2012, 9, 30),
					}*/	
			};
			var graph = new google.visualization.LineChart(document.getElementById('graph'));
			graph.draw(data, options);
		}
		function timeline() {
			var jsonData = $.ajax({
				url: "getJSON.php?style=timeline",
				dataType: "json",
				async: false
			}).responseText;
			var data = new google.visualization.DataTable(jsonData);
			var options = {
			};
			var timeline = new google.visualization.AnnotatedTimeLine(document.getElementById('timeline'));
			timeline.draw(data, options);
		}

		// This will redraw the charts
		setInterval(gauge, 5000);
		setInterval(graph, 5000);
	</script>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div id="container">
		<div id="gauge"></div>
		<div id="graph"></div>
		<div id="timeline"></div>
	</div>
</body>
</html>
