<?php
// Specify your sqlite database name and path
$datapie = array();
$dbname = "sqlite:/var/www/PIRlog.db";
$db = new PDO($dbname);

$query = "SELECT strftime('%s','now','localtime') maintenant, strftime('%s',timestamp) enregistrement FROM TxOccupationBox WHERE motion = 1 ORDER BY timestamp DESC LIMIT 1;";
$result = $db->query($query);

$result->setFetchMode(PDO::FETCH_ASSOC);
while ($row = $result->fetch()) {
	extract($row);
	$secondes = $maintenant - $enregistrement;
	$Heures = floor($secondes / 3600);
	$Minutes = ($secondes / 60) % 60;
	$Secondes = $secondes % 60;
	$datapie[] = array("Heures" => $Heures, "Minutes" => $Minutes , "Secondes" => $Secondes, "secondes" => "$secondes");
}

$data = json_encode($datapie);
echo $data;
?>
