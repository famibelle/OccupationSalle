<?php
// Specify your sqlite database name and path
$datapie = array();
$dbname = "sqlite:/var/www/PIRlog.db";
$db = new PDO($dbname);

$query = "SELECT * FROM TxOccupationBox WHERE motion = 1;";
$result = $db->query($query);

$result->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $result->fetch()) {
	extract($row);
	$datapie[] = array("Timestamp" => $timestamp, "Motion" => $motion);
}
$data = json_encode($datapie);
echo $data;
?>
