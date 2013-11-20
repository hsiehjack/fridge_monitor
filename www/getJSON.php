<?php
$style = $_GET['style'];

$con = mysqli_connect('localhost', 'pi', 'pi', 'pi');
if (!$con) {
	die('Cloud not connect: ' . mysqli_error($con));
}

mysqli_select_db($con, 'pi');

abstract class chart_api {
	abstract public function set_cols();
	abstract public function set_rows($result);
	protected $json;
	public function get_json() {
		return json_encode($this->$json);
	}
}
class gauge extends chart_api {
	public function set_cols() {
		$this->$json['cols'] = array(
		    array('id' => 'Label', 'label' => 'Label', 'type' => 'string'),
		    array('id' => 'Value', 'label' => 'Value', 'type' => 'number')
		);
	}
	public function set_rows($result) {
		while($row = mysqli_fetch_array($result)){ 
			$rows[] = array('c' => 
			array('v' => "Temperature"),
			array('v' => $row['temp'])
			);
		}
		$this->$json['rows'] = $rows;
	}
}
class graph extends chart_api {
	public function set_cols() {
		$this->$json['cols'] = array(
		array('id' => 'DateTime', 'label' => 'DateTime', 'type' => 'string'),
		array('id' => 'Temp', 'label' => 'Temp', 'type' => 'number')
		);
	}
	public function set_rows($result) {
		while($row = mysqli_fetch_array($result)){ 
			$rows[] = array('c' => 
			array('v' => $row['date']),
			array('v' => $row['temp']),
			);
		}
		$this->$json['rows'] = $rows;
	}
}
switch ($style) {
	case "gauge":
		$sql="SELECT temp FROM datetemp ORDER BY id DESC LIMIT 1";
		$result = mysqli_query($con, $sql);
		$obj = new gauge;
		$obj->set_cols();
		$obj->set_rows($result);
		break;

	case "graph":
		$sql="SELECT date, temp FROM datetemp ORDER BY id DESC LIMIT 100";
		$result = mysqli_query($con, $sql);
		$obj = new graph;
		$obj->set_cols();
		$obj->set_rows($result);
		break;
}

mysqli_close($con);
echo $obj->get_json(); 
?>
