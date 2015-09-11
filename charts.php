<script src="Chart.js"></script>
<canvas id="myChart" width="400" height="400"></canvas>
// Get the context of the canvas element we want to select
var ctx = document.getElementById("myChart").getContext("2d");
var myNewChart = new Chart(ctx).PolarArea(data);

// Get context with jQuery - using jQuery's .get() method.
var ctx = $("#myChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.
var myNewChart = new Chart(ctx);

new Chart(ctx).PolarArea(data, options);


<?php
$url = "http://localhost/json.php";
$json = file_get_contents($url);
$json_data = json_decode($json);
//echo "My stats: ". $json;
//echo "My Stats:" . print_r($json_data, true);
?>
