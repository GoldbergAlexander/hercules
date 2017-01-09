<?php
require_once 'dbconnect.php';
require 'security.php';


echo "<div class='chart' id='chart'>";
echo "<div class='toolbar' id='toolbar'>";

echo "<form class='chartform' id='chartform' method='post' >";
echo "<div class='startdate' id='startdate'name='startdate'>";
	echo "<input class='date' type='date'name='startdate'>";
echo "</div>"; //startdate
echo "<div class='detail' id='detail'>";
	echo "<select name='detail'>";
		echo "<option value='day'>Day</option>";
		echo "<option value='month'>Month</option>";
		echo "<option value='year'>Year</option>";
	echo "</select>"; //detail
echo "</div>"; //detail
echo "<div class='location' id='location'>";
	echo "<select name='location'>";
		echo "<option value='All'>All</option>";
	//Prepare
	if(!$stmt = $con->prepare("SELECT Name FROM Location ORDER BY Name ASC")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Bind
	if(!$stmt->bind_result($Name)){
		echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
	}


	while($stmt->fetch()){
		echo "<option value='$Name'>$Name</option>";
	}	
	echo "</select>"; //location
echo "</div>"; //location

echo "<div class='enddate' id='enddate'>";
	echo "<input class='date' type='date' name='enddate'>";
echo "</div>"; //enddate
echo "<div class='submit' id='submit'>";
	echo "<input type='submit' name='submit' value='Load'>";
echo "</div>"; //enddate


echo "</form>"; //form
echo "</div>"; //toolbar

include "data.php";

echo "</div>"; //chart

