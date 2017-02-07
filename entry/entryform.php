<?php
require_once "/var/www/html/database/dbconnect.php";
require_once '/var/www/html/security/security.php'; //login check
secure();

//Get location List
$sql = "SELECT Name FROM Location";
$locationResults = $con->query($sql);
if(!$locationResults){
	echo "Location Query Error";
}

/*
//Get Department List
$sql = "SELECT Name FROM Department";
$departmentResults = $con->query($sql);
if(!$departmentResults){
	echo "Department Query Error";
}*/

$date = time();
if(date("l",$date) == "Monday"){
	$date = $date - 2*60*60*24;
	$date = date("Y-m-d",$date);
}else{
	$date = $date - 60*60*24;
	$date = date("Y-m-d",$date);
}

if(isset($_SESSION['submitdata'])){
	$array = $_SESSION['submitdata'];
	$date = $array['date'];
	$location = $array['location'];
}

echo "<div class='entryform'>";
echo "<form autocomplete='off'id='entryform'>";

echo "<div class='entryforminput'>";
echo "<div>Location</div>";
echo "<select name='location'>";
if(isset($location)){
	echo "<option value='" . $location . "'>" . $location . "</option>";
}else{
	echo "<option value='" . $_SESSION['location'] . "'>" . $_SESSION['location'] . "</option>"; 
}
while($row = $locationResults->fetch_assoc()){
	echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
}
echo "</select>"; 
echo "</div>";

/*
echo "<div class='entryforminput'>";
echo "Department";
echo "<select name='department'>";
while($row = $departmentResults->fetch_assoc()){
	echo "<option value'" . $row['Name'] . "'>" . $row['Name'] . "</option>";
}
echo "</select>";
echo "</div>";
*/

//Set the day back by one day if a workday, if its monday, send it back two days


echo "<div class='entryforminput'>";
echo "<div>Date</div>";
echo "<input type='date' name='date' value='". $date ."'>";
echo "</div>";

if(!isset($array['transcount'])){
	$array['transcount'] = 0;
}

echo "<div class='entryforminput'>";
echo "<div>Transaction Count</div>";
echo "<input type='text' name='transcount' value='". $array['transcount'] ."'>";
echo "</div>";

if(!isset($array['cashcount'])){
	$array['cashcount'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Cash Count</div>";
echo "<input type='text' name='cashcount' value='" . $array['cashcount'] ."'>";
echo "</div>";

if(!isset($array['checkcount'])){
	$array['checkcount'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Check Count</div>";
echo "<input type='text' name='checkcount' value='" . $array['checkcount'] ."'>";
echo "</div>";

if(!isset($array['payout'])){
	$array['payout'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Payout</div>";
echo "<input type='text' name='payout'value='" . $array['payout'] ."'>";
echo "</div>";

if(!isset($array['cardunit'])){
	$array['cardunit'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Card Unit</div>";
echo "<input type='text' name='cardunit'value='" . $array['cardunit'] ."'>";
echo "</div>";

if(!isset($array['cashtape'])){
	$array['cashtape'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Cash Tape</div>";
echo "<input type='text' name='cashtape'value='" . $array['cashtape'] ."'>";
echo "</div>";

if(!isset($array['checktape'])){
	$array['checktape'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Check Tape</div>";
echo "<input type='text' name='checktape'value='" . $array['checktape'] ."'>";
echo "</div>";

if(!isset($array['cardtape'])){
	$array['cardtape'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Card Tape</div>";
echo "<input type='text' name='cardtape'value='" . $array['cardtape'] ."'>";
echo "</div>";

if(!isset($array['taxtape'])){
	$array['taxtape'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Tax Tape</div>";
echo "<input type='text' name='taxtape'value='" . $array['taxtape'] ."'>";
echo "</div>";

if(!isset($array['salesvoid'])){
	$array['salesvoid'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Sales Void</div>";
echo "<input type='text' name='salesvoid'value='" . $array['salesvoid'] ."'>";
echo "</div>";

if(!isset($array['taxvoid'])){
	$array['taxvoid'] = 0.00;
}
echo "<div class='entryforminput'>";
echo "<div>Tax Void</div>";
echo "<input type='text' name='taxvoid'value='" . $array['taxvoid'] ."'>";
echo "</div>";

if(!isset($array['memo'])){
	$array['memo'] = "";
}
echo "<div class='entryforminput'>";
echo "<div>Memo</div>";
echo "<input type='textarea' name='memo' value='" . $array['memo'] . "'>";
echo "</div>";

echo "<div class='entryforminput'>";
echo "<input class='efsubmit' type='submit' name='submit' value='Review'>";
echo "</div>";

echo "</form>";
echo "</div>";

include "/var/www/html/entry/entrybuttons.php";
