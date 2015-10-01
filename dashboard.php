<?php
$dbname = "sqlite:/var/www/PIRlog.db";
$row = array();
for ($jour = 0; $jour < 7; $jour++) {
		for ($heure = 0; $heure < 24; $heure++) {
		// Connect to DB
		$conn = new PDO($dbname);

		// Query
		$Query = "SELECT strftime('%w', timestamp) jour, strftime('%H', timestamp) heure, sum(motion) as mouvements FROM TxOccupationBox WHERE jour ='" . $jour . "' AND heure = '" . $heure . "';";
		echo $Query . "\n";
		$query = $conn->query($Query);
		$row[$jour][$heure] = $query->fetch(PDO::FETCH_ASSOC);
	}
}
echo json_encode($row);
?>
