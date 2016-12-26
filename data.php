<?php 
require_once 'dbconnect.php';
include 'security.php';
//Detail
	//Day
	//Month
	//Year

//Ret
	//Revenue
	//More to follow from dailies

//Date
	//Start
	//End
	//Between

//Location

//Get Vars
	//Period
	//bgn
	//end
	//loc
/*
if(!isset($_GET['period'])){
	echo "Period Not Set";
}
if(!isset($_GET['location'])){
	echo "Location Not Set";
}
if(!isset($_GET['bgn'])){
	echo "BGN Not Set";
}
if(!isset($_GET['end'])){
	echo "END Not Set";
}
*/
//Primary Case
	//Monthly Data
	//All Locations
	//No Date Range

//Prepare
if(!$stmt = $con->prepare("SELECT Name, Year, Month, SUM(Revenue)
			FROM RevenueMonthly
			LEFT JOIN Location
			ON RevMonth_idLocation = idLocation
			GROUP BY Month, Year
			ORDER BY Year DESC
			")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
/*
//Bind
if(!$stmt->bind_param("")){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
*/
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}

//Bind
if(!$stmt->bind_result($location,$year,$month,$revenue)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}
$results = array(); //Store our results array
while($stmt->fetch()){
$arr = array(
	'location' => $location, 
	//'year' => $year,   //Month + Year must be converted to a date for C3.JS 
	//'month' => $month, 
	'date' => date("Y-m-d",mktime(0,0,0,$month,0,$year)),
	'revenue' => $revenue);
array_push($results,$arr); // Push the data to the results
}
echo json_encode($results);
$stmt->close();
$result = NULL;


//End Primary Case






?>
