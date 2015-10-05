<?php
$dbname = "sqlite:/var/www/PIRlog.db";
$datapie = array();
$days = array( 0 => "Lundi", 1 => "Mardi", 2 => "Mercredi", 3 => "Jeudi", 4 => "Vendredi", 5 => "Jeudi", 6 => "Dimanche");
for ($heure = 0; $heure < 24; $heure++) {
	// Connect to DB
	$conn = new PDO($dbname);

	// Query
	$Query = "SELECT (strftime('%H', timestamp) - strftime('%H', timestamp)%2) AS heure, strftime('%w', timestamp) AS jour, sum(motion) AS mouvements FROM TxOccupationBox WHERE 1 GROUP BY heure, jour;";
	$query = $conn->query($Query);
	
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		
		$datapie[ "State" => $row->heure ][ $days[$row->jour] ] = $row->mouvements;
		// Print the line
		// Fetch the next line
		//extract($row);
		//$datapie[] = array("State" => $heure , "jour" => $jour , "value" => $mouvements);
		//$row = $query->fetch(PDO::FETCH_ASSOC);
	}
}
//echo json_encode($row);
$data = json_encode($datapie);
echo $data;

/*
var freqData=[
 {State:'00h',freq:{Lundi:4786, Mardi:1319, Mercredi:249, Jeudi:455, Vendredi:4155, Samedi:125, Dimanche:211}}
,{State:'02h',freq:{Lundi:932, Mardi:2149, Mercredi:418, Jeudi:855, Vendredi:1455, Samedi:125, Dimanche:911}}
,{State:'04h',freq:{Lundi:4481, Mardi:3304, Mercredi:948, Jeudi:655, Vendredi:4515, Samedi:325, Dimanche:711}}
,{State:'06h',freq:{Lundi:119, Mardi:247, Mercredi:123, Jeudi:355, Vendredi:4551, Samedi:1725, Dimanche:611}}
,{State:'08h',freq:{Lundi:189, Mardi:247, Mercredi:103, Jeudi:655, Vendredi:3455, Samedi:6725, Dimanche:511}}
,{State:'10h',freq:{Lundi:819, Mardi:27, Mercredi:1203, Jeudi:855, Vendredi:4655, Samedi:7251, Dimanche:161}}
,{State:'12h',freq:{Lundi:189, Mardi:2347, Mercredi:203, Jeudi:755, Vendredi:4535, Samedi:75, Dimanche:161}}
,{State:'14h',freq:{Lundi:119, Mardi:247, Mercredi:13203, Jeudi:855, Vendredi:4155, Samedi:725, Dimanche:111}}
,{State:'16h',freq:{Lundi:178, Mardi:2147, Mercredi:12073, Jeudi:355, Vendredi:4855, Samedi:2, Dimanche:161}}
,{State:'18h',freq:{Lundi:185, Mardi:2447, Mercredi:12073, Jeudi:1955, Vendredi:4955, Samedi:25, Dimanche:171}}
,{State:'20h',freq:{Lundi:289, Mardi:2467, Mercredi:1253, Jeudi:555, Vendredi:1455, Samedi:7725, Dimanche:1771}}
,{State:'22h',freq:{Lundi:1819, Mardi:3247, Mercredi:1903, Jeudi:855, Vendredi:9455, Samedi:7625, Dimanche:5111}}
,{State:'24h',freq:{Lundi:1819, Mardi:2457, Mercredi:123, Jeudi:155, Vendredi:4855, Samedi:7215, Dimanche:1211}}
];
*/
?>
