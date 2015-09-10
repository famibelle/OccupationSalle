<?php
// Specify your sqlite database name and path
$dbname = "sqlite:/var/www/PIRlog.db";

$db = new PDO($dbname);
$result = $db->query('SELECT * FROM TxOccupationBox');
$datapie = array();
$result->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $result->fetch()) {
	extract($row);
	$datapie[] = array("timestamp" => $timestamp, "motion" => $motion);
}
$data = json_encode($datapie);
echo $data
?>
