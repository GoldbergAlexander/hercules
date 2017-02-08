<?php
require_once '/var/www/html/entry/entryLibrary.php';
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
secure();

$location = filterString($_POST['location']);
$department = filterString($_POST['department']);
$date = filterDate($_POST['date']);
$transcount = filterInt($_POST['transcount']);
$cashcount = filterFloat($_POST['cashcount']);
$checkcount = filterFloat($_POST['checkcount']);
$payout = filterFloat($_POST['payout']);
$cardunit = filterFloat($_POST['cardunit']);
$cashtape = filterFloat($_POST['cashtape']);
$checktape = filterFloat($_POST['checktape']);
$cardtape = filterFloat($_POST['cardtape']);
$taxtape = filterFloat($_POST['taxtape']);
$salesvoid = filterFloat($_POST['salesvoid']);
$taxvoid = filterFloat($_POST['taxvoid']);
$memo = filterString($_POST['memo']);


$locationid = locationToId($con, $location);
$departmentid = departmentToId($con, $department);

$submitarray = array(
	"location" => $location,
	"department" => $department,
	"locationid" => $locationid,
	"departmentid" => $departmentid,
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

