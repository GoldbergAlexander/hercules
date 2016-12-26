<?php

require_once 'dbconnect.php';
require 'security.php';

$location = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
//$department = filter_var($_POST['department'],FILTER_SANITIZE_STRING);
$date = $_POST['date'];
$parse = date_parse($date);
if(!checkdate($parse['month'],$parse['day'],$parse['year'])){
	$date = FALSE;
}
$transcount = filter_var($_POST['transcount'],FILTER_SANITIZE_NUMBER_INT);
$cashcount = filter_var($_POST['cashcount'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$checkcount = filter_var($_POST['checkcount'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$payout = filter_var($_POST['payout'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$cardunit = filter_var($_POST['cardunit'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); 
$cashtape = filter_var($_POST['cashtape'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$checktape = filter_var($_POST['checktape'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$cardtape = filter_var($_POST['cardtape'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$taxtape = filter_var($_POST['taxtape'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$salesvoid = filter_var($_POST['salesvoid'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$taxvoid = filter_var($_POST['taxvoid'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$memo = filter_var($_POST['memo'],FILTER_SANITIZE_STRING);

//Convert location and department to ids
//prepare
if(!$stmt = $con->prepare("SELECT idLocation FROM Location WHERE Name=? LIMIT 1")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//bind
if(!$stmt->bind_param("s",$location)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//bind results
$stmt->bind_result($locationid);
$stmt->fetch();
$stmt->close();
/*
//convert departmetn to id

//prepare
if(!$stmt = $con->prepare("SELECT idDepartment FROM Department WHERE Name=? LIMIT 1")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//bind
if(!$stmt->bind_param("s",$department)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//bind results
//fetch
$stmt->bind_result($departmentid);
$stmt->fetch();
$stmt->close();
 */

$submitarray = array(
	"location" => $location,
	//"department" => $department,
	"locationid" => $locationid,
	//"departmentid" => $departmentid,
	"date" => $date,
	"transcount" => $transcount,
	"cashcount" => $cashcount,
	"checkcount" => $checkcount,
	"payout" => $payout,
	"cardunit" => $cardunit,
	"cardtape" => $cardtape,
	"cashtape" => $cashtape,
	"checktape" => $checktape,
	"taxtape" => $taxtape,
	"salesvoid" => $salesvoid,
	"taxvoid" => $taxvoid,
	"memo" => $memo
);
if(!$submitarray){
	echo "valid";
}

if(!($_SESSION['submitdata'] = $submitarray)){
	echo "error";
}

