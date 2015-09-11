<!DOCTYPE html>
<html>
	<head>
		<title>Taux d'occupation des Box du 7A</title>
		<meta name="description" content="chart created using amCharts live editor" />
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

		<!-- amCharts javascript sources -->
		<script src="http://www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
		<script src="http://www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>

		<!-- amCharts javascript code -->
		<script type="text/javascript">
			AmCharts.makeChart("chartdiv",
				{
					"type": "serial",
					"categoryField": "category",
					"dataDateFormat": "YYYY-MM-DD hh:mm:ss",
					"startDuration": 1,
					"theme": "default",
					"categoryAxis": {
						"autoRotateAngle": 0,
						"autoRotateCount": 0,
						"autoWrap": true,
						"firstDayOfWeek": 0.96,
						"gridPosition": "start",
						"minPeriod": "ss",
						"minHorizontalGap": 72
					},
					"chartCursor": {},
					"chartScrollbar": {},
					"trendLines": [],
					"graphs": [
						{
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "mouvements"
						}
					],
					"guides": [],
					"valueAxes": [
						{
							"id": "ValueAxis-1",
							"title": "nombre de mouvements détéctés"
						}
					],
					"allLabels": [],
					"balloon": {},
					"titles": [
						{
							"id": "Title-1",
							"size": 15,
							"text": "Mouvements détectés"
						}
					],
 					"export": {
						"enabled": true
					},
					"dataProvider": [

<?php
// Code to query an SQLite database and return results as JSON.

// Specify your sqlite database name and path
$dir = 'sqlite:PIRlog.db';

// Instantiate PDO connection object and failure msg
$dbh = new PDO($dir) or die("cannot open database");

// Define your SQL statement
//$query = "SELECT * FROM TxOccupationBox";
$query = "SELECT datetime((strftime('%s', timestamp) / 900) * 900, 'unixepoch') interval, sum(motion) cnt FROM TxOccupationBox GROUP by interval ORDER by interval";
//$results  = $dbh->query($query);
//echo $results;

// Iterate through the results and pass into JSON encoder
foreach ($dbh->query($query) as $row) {
        //echo json_encode($row);
        echo "{\n";
        echo "\t";
        echo "'mouvements':";   echo json_encode($row[1]);
        echo ",";
        echo "\n";
        echo "\t";
        echo "'category':";     echo json_encode($row[0]);
        echo "\n";
        echo "},";
}
?>
]
}
);
		</script>
	</head>
	<body>
		<div id="chartdiv" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
	</body>
</html>
