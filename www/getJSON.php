<?php
$style = $_GET['style'];

$con = mysqli_connect('localhost', 'pi', 'pi', 'pi');
if (!$con) {
	die('Cloud not connect: ' . mysqli_error($con));
}

mysqli_select_db($con, 'pi');
$sql="SELECT date, temp FROM datetemp ORDER BY id DESC LIMIT 1";

$result = mysqli_query($con, $sql);

switch ($style) {
	case "gauge":
		$table = <<<JSONHERE
{
	"cols": [
		{"id": "Label", "label": "Label", "type":"string"},
		{"id": "Value", "label": "Value", "type":"number"}
	],
	"rows": [
JSONHERE;
		while ($row = mysqli_fetch_assoc($result)) {
			$table .= "{\"c\":[{\"v\": \"Temperature\"}, {\"v\": " . $row['temp'] . "}]}";
		}
		$table .= "]}";
		break;
}

/*
while ($row = mysqli_fetch_array($result)) {
print $row[0] . '<br />';
print $row[1];
}
*/

echo $table;

mysqli_close($con);
?>
