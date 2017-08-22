<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/display/displayLibrary.php';
secure();

echo input();

if(!isset($_SESSION['display'])){

}else {
    $date = $_SESSION['display']['date'];
    $location = $_SESSION['display']['location'];
    $user = $_SESSION['username'];


    echo "<div class='display_generation_details'>";
    echo "<span class='display_header'>Report Generation Details</span>";
    echo "</br>";
    echo "Location: " . $location;
    echo "</br>";
    echo "For date: " . $date;
    echo "</br>";
    echo "Accessed by: " . $user;
    echo "</br>";
    echo "Accessed on: " . date("Y-m-d H:i:s");
    echo "</br>";
    echo "</div>"; //display_generation_details

    echo @explainDay_One($date, $location);
}







/* Chart display -- Saved for later
echo "<div class='chart' id='chart'>";
echo "<div class='toolbar' id='toolbar'>";

echo "<form class='chartform' id='chartform' method='post' >";
echo "<div class='startdate' id='startdate'name='startdate'>";
	echo "<input class='date' value='1900-01-01' type='date'name='startdate'>";
echo "</div>"; //startdate
echo "<div class='detail' id='detail'>";
	echo "<select name='detail'>";
		echo "<option value='day'>Day</option>";
		echo "<option value='month' selected>Month</option>";
		echo "<option value='year'>Year</option>";
	echo "</select>"; //detail
echo "</div>"; //detail
echo "<div class='location' id='location'>";
	echo "<select name='location'>";
		echo "<option value='Combined' selected >Combined</option>";
		echo "<option value='Compared' selected >Compared</option>";
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
echo "<div class='type' id='type'>";
	echo "<select name='type'>";
		echo "<option value='column2d' selected>Column</option>";
		echo "<option value='bar2d'>Bar</option>";
		echo "<option value='line'>Line</option>";
		echo "<option value='area2d'>Area</option>";
	echo "</select>"; //detail
echo "</div>"; //detail

echo "<div class='enddate' id='enddate'>";
	echo "<input class='date'value='2100-01-10' type='date' name='enddate'>";
echo "</div>"; //enddate
echo "<div class='submit' id='submit'>";
	echo "<input type='submit' name='submit' value='Load'>";
echo "</div>"; //enddate


echo "</form>"; //form
echo "</div>"; //toolbar


//From Data.php
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
    $data = dataFetch("month","1900-01-01","2100-01-01","West Side",NULL);
    dataDisplay("column2d",$data);
}

echo "</div>"; //chart

*/ // Chart Display -- Saved for later

