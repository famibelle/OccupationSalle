<?php
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
        echo "Aucun mouvement depuis " . gmdate("H:i:s", $secondes);
        if ($secondes> 300) {
                echo "<p>le box est vide depuis " . $Heures . " heures " . $Minutes .  " minutes et "  . $Secondes . " secondes";
        }
        else {
                echo "<p>le box est occupé";
        }
}
?>
