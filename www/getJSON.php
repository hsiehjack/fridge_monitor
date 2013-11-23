<?php
$style = $_GET['style'];
$con = mysqli_connect('localhost', 'pi', 'pi', 'pi');

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, 'pi');
abstract class chart_api {
	protected $json;
	abstract public function insert_col($col);
	abstract public function insert_row($row);
	public function get_json() {
		return json_encode($this->json);
	}
}
class graph extends chart_api {
	public function insert_col($col) {
		$this->json['cols'][] = $col;
	}
	public function insert_row($row) {
		$this->json['rows'][] = $row;
	}
}
switch ($style) {
	case "gauge":
		$sql="SELECT temp FROM datetemp ORDER BY id DESC LIMIT 1";
		$result = mysqli_query($con, $sql);
		$obj = new graph;
		$obj->insert_col(array('id' => 'Label', 'label' => 'Label', 'type' => 'string'));
		$obj->insert_col(array('id' => 'Value', 'label' => 'Value', 'type' => 'number'));
		while($row = mysqli_fetch_array($result)){ 
			$obj->insert_row(array('c' => array(
				array('v' => "Temperature"),
				array('v' => $row['temp'])
				)));
		}
		break;
	case "graph":
//		$sql="SELECT date, temp FROM datetemp ORDER BY id LIMIT 100";
		$sql="SELECT * FROM (SELECT * FROM datetemp ORDER BY id DESC LIMIT 100) this ORDER BY this.id";
		$result = mysqli_query($con, $sql);
		$obj = new graph;
		$obj->insert_col(array('id' => 'DateTime', 'label' => 'DateTime', 'type' => 'datetime'));
		$obj->insert_col(array('id' => 'Temp', 'label' => 'Temp', 'type' => 'number'));
		while($row = mysqli_fetch_array($result)){ 
			$obj->insert_row(array('c' => array(
				array('v' => new Date($row['date'])),
				array('v' => $row['temp'])
				)));
		}
		break;
}
mysqli_close($con);
echo $obj->get_json(); 
