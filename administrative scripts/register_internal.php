<?php
include_once "/var/www/html/database/dbconnect.php";
//Registration Function
/*
if(!isset($_POST['username']) || !isset($_POST['password'])){
	die("Expected Username and Password");
}
 */
$id; //DB defined
$username = "Test";//$_POST["username"]; //User Def
$password = "Test";//$_POST["password"]; //User Def
$hash; //System Def
$level; //Admin Def

$hash = password_hash($password,PASSWORD_DEFAULT);
$level = 0;
$username = mysqli_real_escape_string($con,$username);

//Prepare
if(!($stmt = $con->prepare("INSERT INTO Users (Username,Hash,level) VALUES (?,?,?)"))){
	echo "Prepare Failed: (" . $con->errno . ") "  . $stmt->error;
}
//Bind
if(!$stmt->bind_param("ssi",$username,$hash,$level)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ")" . $stmt->error;
}
$stmt->close();
?>

//test
/*
$var = 1;
for($x = 0; $x <365*5; $x++){
$location 	= "Not the West Side";
$locationid	= 2;
//$department 	= $array['department'];
//$departmentid	= $array['departmentid'];
$date 		= date("Y-m-d" ,strtotime("-$x day",strtotime(time())));
$transcount	= 1;
$cashcount 	= 1;
$checkcount 	= 1;
$payout 	= 1;
$cardunit 	= 1;
$cashtape 	= 1;
$checktape 	= 1;
$cardtape 	= 1;
$taxtape 	= 1;
$salesvoid 	= 1;
$taxvoid 	= 1;
$memo		= 1;
//*/