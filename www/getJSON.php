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
		$table = array(
    		'cols' => array(
        		'id' => 'Label', 'label' => 'Label', 'type' => 'string',
        		'id' => 'Value', 'label' => 'Value', 'type' => 'number'
    		),
    		'rows' => array()
		);
		while ($row = mysqli_fetch_assoc($result)) {
    		$table['rows'][] = 
        		'c' => array(
            		'v' => $row['date'],
            		'v' => $row['temp']
  	 		);
		}
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
