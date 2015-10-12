<?php
$dbname = "sqlite:/var/www/PIRlog.db";
$datapie = array();
$jourSemaine = array( 0 => "Dimanche", 1 => "Lundi", 2 => "Mardi", 3 => "Mercredi", 4 => "Jeudi", 5 => "Vendredi", 6 => "Samedi");
$vecteurState = array( 0=>"00h" , 2=>"02h", 4=>"04h", 6=>"06h", 8=>"08h", 10=>"10h", 
	12=>"12h", 14=>"14h", 16=>"16h", 18=>"18h", 20=>"20h" , 22=>"22h", 24=>"24h");

// Connect to DB
$conn = new PDO($dbname);

// Query
//%H 		hour: 00-24
//%w 		day of week 0-6 with Sunday==0
//$Query = "SELECT (strftime('%H', timestamp) - strftime('%H', timestamp)%2) AS heure, strftime('%w', timestamp) AS jour, sum(motion) AS mouvements FROM TxOccupationBox WHERE 1 GROUP BY heure, jour;";
$Query = "SELECT heure, jour, (mouv) from FreqDummy LEFT JOIN (SELECT (strftime('%H', timestamp) - strftime('%H', timestamp)%2) AS hour, strftime('%w', timestamp) AS day, sum(motion) AS mouv FROM TxOccupationBox WHERE 1 GROUP BY hour, day) ON hour = heure AND jour = day;";

$query = $conn->query($Query);

$prevHeure = false;

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

	extract($row);

	if ($heure !== $prevHeure && $heure) {
		if ( $prevHeure !== false ) {
			$datapie[] = array( "State" => $vecteurState[$prevHeure], "freq" => $days );
		}
		$days = array();
		$prevHeure = $heure;
	}

	if ($jour && $heure) $days[ $jourSemaine[intval($jour)] ] = $mouvements;

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
