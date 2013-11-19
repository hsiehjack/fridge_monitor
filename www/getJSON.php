<?php
$link = mysql_connect('pi', 'pi', 'pi')
    or die('Could not connect: ' . mysql_error());
mysql_select_db('pi') or die('select db error');

$query = 'SELECT DATE, TEMP FROM DATETEMP';
$result = mysql_query($query) or die(mysql_error());

$table = array(
    'cols' => array(
        array('label' => 'date', 'type' => 'timestamp'),
        array('label' => 'temp', 'type' => 'number')
    ),
    'rows' => array()
);
while ($row = mysql_fetch_assoc($result)) {
    $table['rows'][] = array(
        'c' => array(
            array('v' => $row['date']),
            array('v' => $row['temp'])
        )
    );
}
mysql_close($link);
echo json_encode($table); 
?>
