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
        echo "Aucun mouvement depuis " . gmdate("H:i:s", $secondes);
}
?>
