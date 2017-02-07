<?php 
require_once '/var/www/html/database/dbconnect.php';
include "/var/www/html/fc/php-wrapper/fusioncharts.php";

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
			"exportenabled" => "1",
			"exportatclientside" => "1"	
			);
	$results['data'] = $data;
	$json = json_encode($results);
	echo "<div class='chart-1' id='chart-1'></div>";

	//Convert Human Readable Chart Types to real type
	//Types: Column, Bar, Line, Area
	if($type == NULL){
		$type = "column2d";
	}		
	$columnChart = new FusionCharts($type,"Revenue Chart", 800,300, "chart-1","json",$json);
	$columnChart->render();

}

/*
function dataDisplaySet($type,$data,$names){
	$results["dataset"] = array();
	$results['catagory'] = array();
	
	foreach(array_combine($names,$data) as $name => $set){
		$myData = array();
		$myData['seriesname'] = $name;
		$myData['data'] = $set;
		array_push($results["dataset"],$myData);
	}
	//Find Intersection
	$listOfCatagories = array();
	foreach($data as $set){
		foreach($set as $label){
			array_push($listOfCatagories,$label);
		} 
	}
	var_dump($listOfCatagories);
	
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
			"exportenabled" => "1",
			"exportatclientside" => "1"	
			);
	echo $json = json_encode($results);
	

	echo "<div class='chart-1' id='chart-1'></div>";

	//Convert Human Readable Chart Types to real type
	//Types: Column, Bar, Line, Area
	if($type == NULL){
		$type = "column2d";
	}		
	$columnChart = new FusionCharts($type,"Revenue Chart", 800,300, "chart-1","json",$json);
	//$columnChart->render();

}

*/

?>
