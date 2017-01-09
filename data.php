<?php 
require_once 'dbconnect.php';
include 'security.php';
include 'dataFetch.php';
include 'dataDisplay.php';

if(isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['detail'])){

	$start = filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS);
	$end = filter_var($_POST['enddate'], FILTER_SANITIZE_SPECIAL_CHARS);
	$detail = filter_var($_POST['detail'], FILTER_SANITIZE_SPECIAL_CHARS);
	$location = filter_var($_POST['location'], FILTER_SANITIZE_SPECIAL_CHARS);
	
	if($location == "All"){
		$location = NULL;
	}
	$data = dataFetch($detail,$start,$end,$location,NULL);
	dataDisplay(NULL,$data);
}else{
	$data = dataFetch("month","1900-01-01","3000-01-01","West Side",NULL);
	dataDisplay(NULL,$data);
}
?>
