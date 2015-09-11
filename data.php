<?php
// Specify your sqlite database name and path
$datapie = array();
$dbname = "sqlite:/var/www/PIRlog.db";
$db = new PDO($dbname);
$query = "SELECT strftime('%w', timestamp) day, CAST(strftime('%H', timestamp) as INTEGER) hour, sum(motion) value FROM TxOccupationBox GROUP by hour ORDER by hour;";
$result = $db->query($query);

$result->setFetchMode(PDO::FETCH_ASSOC);
echo "day,hour,value\n";
while ($row = $result->fetch()) {
	echo $row['day'] . "," . $row['hour'] . "," . $row['value'] . '\n';
}
//$comma_separated = implode(",", $datapie);
//echo $comma_separated;

?>
