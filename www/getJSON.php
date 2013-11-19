<?php
$link = mysql_connect('pi', 'pi', 'pi')
    or die('Could not connect: ' . mysql_error());
mysql_select_db('pi') or die('select db error');

$query = 'SELECT * FROM DATETEMP';
$result = mysql_query($query) or die(mysql_error());

$table = array(
    'cols' => array(
        array('label' => 'priority', 'type' => 'string'),
        array('label' => 'num_count', 'type' => 'number')
    ),
    'rows' => array()
);
while ($row = mysql_fetch_assoc($result)) {
    $table['rows'][] = array(
        'c' => array(
            array('v' => $row['priority']),
			array('v' => (int) $row['num_count'])  //Using this because PHP server with host is out of date
        )
    );
}
mysql_close($link);
echo json_encode($table); 
?>
