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
		$table = array(
    		'cols' => array(
        		'id' => 'Label', 'label' => 'Label', 'type' => 'string',
        		'id' => 'Value', 'label' => 'Value', 'type' => 'number'
    		),
    		'rows => array()
		);
		while ($row = mysqli_fetch_assoc($result)) {
    		$table['rows'][] = 
        		'c' => array(
            		'v' => $row['date'],
            		'v' => $row['temp']
  	 		);
		}
		break;
}

mysqli_close($con);
echo json_encode($table); 
?>
