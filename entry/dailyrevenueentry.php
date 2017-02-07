<?php
include_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php'; //login check
secure();

echo "<div class='entryrec'>";

if(!isset($_SESSION['submitdata'])){
	die("Error no stored Data");
}
$array = $_SESSION['submitdata'];

$location 	= $array['location'];
$locationid	= $array['locationid'];
//$department 	= $array['department'];
//$departmentid	= $array['departmentid'];
$date 		= $array['date'];
$transcount	= $array['transcount'];
$cashcount 	= $array['cashcount'];
$checkcount 	= $array['checkcount'];
$payout 	= $array['payout'];
$cardunit 	= $array['cardunit'];
$cashtape 	= $array['cashtape'];
$checktape 	= $array['checktape'];
$cardtape 	= $array['cardtape'];
$taxtape 	= $array['taxtape'];
$salesvoid 	= $array['salesvoid'];
$taxvoid 	= $array['taxvoid'];
$memo		= $array['memo'];


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

//Prepare
if(!$stmt = $con->prepare("INSERT INTO DailyRevenueEntry (DailyRevEntry_idLocation,Date,TransCount, CashCount,CheckCount,PayoutReceipt,CardUnit,CashTape,CheckTape,CardTape,TaxTape,SalesVoid,TaxVoid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}

//Add ablity for array handling
//Bind
if(!$stmt->bind_param("issssssssssss",$locationid, $date,$transcount, $cashcount,$checkcount,$payout,$cardunit,$cashtape,$checktape,$cardtape,$taxtape,$salesvoid,$taxvoid)){
	echo "Bind Failed: (" . $stmt->errno. ") " . $stmt->error;
}
 
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (". $stmt->errno . ") " .$stmt->error;
}
$stmt->close();

//Get the last entry id
$entryid = $con->insert_id;

//Set Confirmation

$IP = $_SERVER['REMOTE_ADDR'];
$IP = ip2long($IP);

$userid;

//Get user id

//prepare
if(!$stmt = $con->prepare("SELECT idUsers FROM Users WHERE Username=? LIMIT 1")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//bind
if(!$stmt->bind_param("s",$_SESSION['username'])){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}

//bind results
//fetch
$stmt->bind_result($userid);
$stmt->fetch();
$stmt->close();


$datetime = date('Y-m-d H:i:s');

//Insert Confirmation
//prepare
if(!$stmt = $con->prepare("INSERT INTO Confirmation (Con_idDailyRevenueEntry,Con_idUsers,Datetime,IP) VALUES (?,?,?,?)")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//bind
if(!$stmt->bind_param("iiss",$entryid,$userid,$datetime,$IP)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->close();

//Set Memo

//using entryid and memo
if($memo != ''){
//prepare
if(!$stmt = $con->prepare("INSERT INTO Memo (Memo_idDailyRevEntry,Data) VALUES (?,?)")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//bind
if(!$stmt->bind_param("is",$entryid,$memo)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->close();
}
echo "The data has been saved";

echo "</div>";
include "/var/www/html/entry/entrybuttons.php";

