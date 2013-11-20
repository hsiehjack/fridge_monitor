<?php
$style = $_GET['style'];

$con = mysqli_connect('localhost', 'pi', 'pi', 'pi');
if (!$con) {
	die('Cloud not connect: ' . mysqli_error($con));
}

mysqli_select_db($con, 'pi');

switch ($style) {
	case "gauge":
		$sql="SELECT temp FROM datetemp ORDER BY id DESC LIMIT 1";
		$result = mysqli_query($con, $sql);

		$table['cols'] = array(
		    array('id' => 'Label', 'label' => 'Label', 'type' => 'string'),
		    array('id' => 'Value', 'label' => 'Value', 'type' => 'number')
		);
		while($row = mysqli_fetch_array($result)){ 
			$data = array();
			$data[] = array('v' => $row['date']);
			$data[] = array('v' => $row['temp'];
			$rows[] = array('c' => $data);
		}
		$table['rows'] = $rows;
		break;

	case "graph":
		$sql="SELECT date, temp FROM datetemp ORDER BY id DESC LIMIT 100";
		$result = mysqli_query($con, $sql);
		$table = <<<JSONHERE
{
	"cols": [
		{"id": "DateTime", "label": "DateTime", "type":"string"},
		{"id": "Temp", "label": "Temp", "type":"number"}
	],
	"rows": [
JSONHERE;
		$count = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			if ($count++ > 0) $table .= ",";
			$table .= "{\"c\":[{\"v\": \"" . $row['date'] . "\"}, {\"v\": " . $row['temp'] . "}]}";
		}
		$table .= "]}";
		break;
}

mysqli_close($con);
echo json_encode($table); 
?>
