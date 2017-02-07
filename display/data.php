<?php 
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
include '/var/www/html/display/dataDisplay.php';
include '/var/www/html/display/dataFetch.php';
secure();

if(isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['detail'])){

	$start = filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS);
	$end = filter_var($_POST['enddate'], FILTER_SANITIZE_SPECIAL_CHARS);
	$detail = filter_var($_POST['detail'], FILTER_SANITIZE_SPECIAL_CHARS);
	$location = filter_var($_POST['location'], FILTER_SANITIZE_SPECIAL_CHARS);	
	$type = filter_var($_POST['type'], FILTER_SANITIZE_SPECIAL_CHARS);
	
	//Here is the user asked for compared locations we will add multiple data sets
	if($location == "Combined"){
		$location = NULL;
		$data = dataFetch($detail,$start,$end,$location,NULL);
		dataDisplay($type,$data);
	}else if($location == "Compared"){
		echo "This Feature is Not Yet Supported";	
	}else{
		$data = dataFetch($detail,$start,$end,$location,NULL);
		dataDisplay($type,$data);
	}

}else{
	$data = dataFetch("month","1900-01-01","3000-01-01","West Side",NULL);
	dataDisplay("column2d",$data);
}
?>
