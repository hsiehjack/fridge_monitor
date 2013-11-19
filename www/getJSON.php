<?php
$q = intval($_GET['q']);

$con = mysqli_connect('localhost', 'pi', 'pi', 'pi');
if (!$con) {
	die('Cloud not connect: ' . mysqli_error($con));
}

mysqli_select_db($con, 'pi');
$sql="SELECT date, temp FROM datetemp ORDER BY id DESC LIMIT 1";

$result = mysqli_query($con, $sql);

while ($row = mysqli_fetch_array($result)) {
print $row[0] . '<br />';
print $row[1];
}

//echo json_encode($table);

mysqli_close($con);
?>
