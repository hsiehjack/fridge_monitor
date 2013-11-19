<!DOCTYPE html>
<html>
<head>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages:['gauge']});
		google.setOnLoadCallback(drawChart);
                var jsonData = $.ajax({
                        url: "getJSON.php",
                        dataType:"json",
                        async: false
                        }).responseText;
		function drawChart() {
			var data = google.visualization.arrayToDataTable(jsonData);
			var options = {
				width: 300, height: 300,
				greenFrom: 32, greenTo: 40,
				yellowFrom:40, yellowTo: 50,
				redFrom: 50, redTo: 80,
				minorTicks: 10, majorTicks: [30, 40, 50, 60, 70, 80],
				min: 30, max: 80,
			};

			var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
		setInterval(drawChart, 5000);
	</script>
</head>
<body>
	<div id="chart_div"></div>
</body>
</html>
