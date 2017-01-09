<?php 
require_once 'dbconnect.php';
include 'security.php';
include "fc/php-wrapper/fusioncharts.php";

function dataDisplay($type,$data){
	$results["data"] = array();

	//Chart Build
	$results["chart"] = array(
			"numberPrefix"=>"$",
			"rotateValues" => "1",
			"placevaluesInside" => "1",
			"labelDisplay" => "1",
			"showValues" => "0",
			"valueFont" => "andale mono",
			"formatNumberScale" => "1",
			"labelStep" => "1",
			"valueAlpha" => "90",
			"canvasPadding" => "10",
			"theme" => "ocean",	
			);
	$results["data"] = $data;
	
	$json = json_encode($results);

	echo "<div class='chart-1' id='chart-1'></div>";

	$columnChart = new FusionCharts("column2d","Revenue Chart", 800,300, "chart-1","json",$json);
	$columnChart->render();

}

?>
