<?php
require_once "/var/www/html/fc/php-wrapper/fusioncharts.php";
require_once '/var/www/html/database/dbconnect.php';

//** From entry library */
function bad($num){
    return "<span class = 'bad'>" . $num . "</span>";
}
function good($num){
    return "<span class = 'good'>" . $num . "</span>";
}
function num($num,  $pg = TRUE, $pre = '$'){ //P ositve is G ood
    $nnn = TRUE;
    if($pre == '%'){
        $num = $num * 100;
    }
    $num = round($num, 2);
    if ($num < 0){
        $num *= -1;
        $nnn = FALSE; // N umber is N ot N egitive
        $num = '(' . $num . ')';
    }
    $num = $pre  . $num;
    if(($pg && $nnn) || (!$pg && !$nnn)){
        $num = good($num);

    }else if (($pg && !$nnn) || (!$pg && $nnn)){
        $num = bad($num);
    }
    return $num;

}

function filterString($input){
    return filter_var($input,FILTER_SANITIZE_STRING);
}
/**
 * @param $date
 * @return $parse
 */
function filterDate($date)
{
    $parse = date_parse($date);
    if (!checkdate($parse['month'], $parse['day'], $parse['year'])) {
        $date = NULL;
    }
    return $date;
}

/**
 * @param $con
 * @return mixed
 */
function getLocations($con)
{
//Get location List
    $sql = "SELECT Name FROM Location";
    $locationResults = $con->query($sql);
    if (!$locationResults) {
        echo "Location Query Error";
        return $locationResults;
    }
    return $locationResults;
}

/** Display a date input bar for users to query data with an optional location selection */
function input(){
    global $con;
    $location = getLocations($con);
    $string = "";
    $string .= "<div class='display_input'>";
    $string .= "<form class='data_selection' id='data_selection_form'>";
        $string .= "<select name='location'>";
        while ($row = $location->fetch_assoc()) {
            $string .= "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
        }
        $string .= "</select>";
    $string .= "<input class='data_selection_input' id='data_selection_input' type='date' name='date'></input>";
    $string .= "<input class='data_selection_submit' id='data_selection_submit' type='submit' name='submit'></input>";
    $string .= "</form>";
    $string .= "</div>"; //Display_input
    return $string;
}

/** Fetch All Revenue Data including Entry Data, Calculated Data, Memo, and Confirmation */
/** a bit of a bad name here. This fetches 'all' the columns. It still only returns a single row */
function fetchAll($date, $location){
    global $con;
    if(!$stmt = $con->prepare("
    				SELECT 
                    RevenueDaily.Date, 
                    Location.Name AS Location,
                    Actual_Intake, 
                    Actual_Pre_Tax_Intake,
                    Actual_Taxable_Intake,
                    Actual_Tax_Intake,
                    Tape_Intake,
                    Tape_Pre_Tax_Intake,
                    Tape_Taxable_Intake,
                    TransCount,
                    CashCount,
                    CheckCount,
                    CardUnit,
                    PayoutReceipt,
                    CashTape,
                    CheckTape,
                    CardTape,
                    TaxTape,
                    VehicleSale,
                    SalesVoid,
                    TaxVoid,
                    Memo.Data AS Memo,
                    Username,
                    Confirmation.Datetime AS Submited,
                    IP AS IP
                    FROM RevenueDaily
                    LEFT JOIN DailyRevenueEntry
                    ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
                    LEFT JOIN Memo
                    ON idDailyRevenueEntry = Memo_idDailyRevEntry
                    LEFT JOIN Confirmation
                    ON idDailyRevenueEntry = Con_idDailyRevenueEntry
                    LEFT JOIN Users
                    ON idUsers = Con_idUsers
                    LEFT JOIN Location
                    ON idLocation = RevDaily_idLocation
                    WHERE RevenueDaily.Date = ?
                    AND Location.Name = ?
			")){
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Bind
    if(!$stmt->bind_param("ss",$date,$location)){
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Execute
    if(!$stmt->execute()){
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind Result
    if(!$res = $stmt->get_result()){
        echo "Get Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$result = $res->fetch_assoc()){
        echo "Fetch Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    return $result;
}

function fetchMTD($date, $location){
    global $con;
    if(!$stmt = $con->prepare("
    				SELECT YEAR(rd.Date) AS Year,MONTH(rd.Date) AS Month, Name AS Location, SUM(Actual_Pre_Tax_Intake) AS MTD, SUM(TransCount) AS Transactions
                    FROM RevenueDaily rd 
                    JOIN Location 
                    ON idLocation = RevDaily_idLocation
                    LEFT JOIN DailyRevenueEntry
                    ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
                    WHERE Name = ?
                    AND YEAR(rd.Date) = YEAR(?)
                    AND MONTH(rd.Date) = MONTH(?)
                    AND DAY(rd.Date) <= DAY(?)
			")){
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Bind
    if(!$stmt->bind_param("ssss",$location,$date,$date,$date)){
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Execute
    if(!$stmt->execute()){
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind Result
    if(!$res = $stmt->get_result()){
        echo "Get Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$result = $res->fetch_assoc()){
        echo "Fetch Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    return $result;
}

function fetchMonthTotal($date, $location){
    global $con;
    if(!$stmt = $con->prepare("
    				SELECT YEAR(rd.Date) AS Year,MONTH(rd.Date) AS Month, Name AS Location, SUM(Actual_Pre_Tax_Intake) AS `MT`, SUM(TransCount) AS Transactions
                    FROM RevenueDaily rd 
                    JOIN Location 
                    ON idLocation = RevDaily_idLocation
                    LEFT JOIN DailyRevenueEntry
                    ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
                    WHERE Name = ?
                    AND YEAR(rd.Date) = YEAR(?)
                    AND MONTH(rd.Date) = MONTH(?)
			")){
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Bind
    if(!$stmt->bind_param("sss",$location,$date,$date)){
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Execute
    if(!$stmt->execute()){
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind Result
    if(!$res = $stmt->get_result()){
        echo "Get Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$result = $res->fetch_assoc()){
        echo "Fetch Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    return $result;
}

function fetchFYTD($date, $location){
    global $con;
    if(!$stmt = $con->prepare('
    				SELECT YEAR(rd.Date) AS Year, Name AS Location, SUM(Actual_Pre_Tax_Intake) AS FYTD, SUM(TransCount) AS Transactions
                    FROM RevenueDaily rd
                    JOIN Location 
                    ON idLocation = RevDaily_idLocation
                    LEFT JOIN DailyRevenueEntry
                    ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
                    WHERE Name = ?
                    AND FYEAR(rd.Date) = FYEAR(?)
                    AND DAYOFYEAR(rd.Date) <= DAYOFYEAR(?)
			')){
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Bind
    if(!$stmt->bind_param("sss",$location,$date,$date)){
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Execute
    if(!$stmt->execute()){
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind Result
    if(!$res = $stmt->get_result()){
        echo "Get Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$result = $res->fetch_assoc()){
        echo "Fetch Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    return $result;
}

function fetchMonths($startDate = '1900-01-01',$endDate = '2100-01-01', $location = ""){
    global $con;
    $location = '%' . $location . '%';
    if(!$stmt = $con->prepare("
    				SELECT YEAR(rd.Date) AS Year,MONTH(rd.Date) AS Month, Name AS Location, SUM(Actual_Pre_Tax_Intake) AS `MT`, SUM(TransCount) AS Transactions
                    FROM RevenueDaily rd 
                    JOIN Location 
                    ON idLocation = RevDaily_idLocation
                    LEFT JOIN DailyRevenueEntry
                    ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
                    WHERE Name LIKE ?
                    AND rd.Date BETWEEN ? AND ?
                    GROUP BY Year, Month, Name
			")){
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Bind
    if(!$stmt->bind_param("sss",$location,$startDate,$endDate)){
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Execute
    if(!$stmt->execute()){
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind Result
    if(!$res = $stmt->get_result()){
        echo "Get Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$result = $res->fetch_all(MYSQLI_ASSOC)){
        echo "Fetch Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    return $result;
}

function fetchFYears($startDate = '1900-01-01',$endDate = '2100-01-01', $location = ""){
    global $con;
    $location = '%' . $location . '%';
    if(!$stmt = $con->prepare("
    				SELECT FYEAR(rd.Date) AS FYear, Name AS Location, SUM(Actual_Pre_Tax_Intake) AS FYT, SUM(TransCount) AS Transactions
                    FROM RevenueDaily rd 
                    JOIN Location 
                    ON idLocation = RevDaily_idLocation
                    LEFT JOIN DailyRevenueEntry
                    ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
                    WHERE Name LIKE ?
                    AND rd.Date BETWEEN ? AND ?
                    GROUP BY FYear, Name
			")){
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Bind
    if(!$stmt->bind_param("sss",$location,$startDate,$endDate)){
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Execute
    if(!$stmt->execute()){
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind Result
    if(!$res = $stmt->get_result()){
        echo "Get Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$result = $res->fetch_all(MYSQLI_ASSOC)){
        echo "Fetch Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();

    return $result;
}

/**
 * @param $date
 * @param $location
 */
function explainDay($date, $location)
{
    $string = "";
    $date2 = date('Y-m-d', strtotime($date . '-1 year'));

    $currentMTD = fetchMTD($date, $location);
    $pastMTD = fetchMTD($date2, $location);
    $currentMT = fetchMonthTotal($date, $location);
    $pastMT = fetchMonthTotal($date2, $location);
    $currentFYTD = fetchFYTD($date, $location);
    $pastFYTD = fetchFYTD($date2, $location);


    $goal = 10;

    $string .= "</br>";
    $string .= "Current month to date: $" . $currentMTD['MTD'];
    $string .= "</br>";
    $string .= "Current month to date average transaction value: $" . $currentMTD['MTD'] / $currentMTD['Transactions'];
    $string .= "</br>";
    $string .= "Prior year month to date: $" . $pastMTD['MTD'];
    $string .= "</br>";
    $string .= "Priot month to date average transaction value: $" . $pastMTD['MTD'] / $pastMTD['Transactions'];
    $string .= "</br>";
    $string .= "Current year month total: $" . $currentMT['MT'];
    $string .= "</br>";
    $string .= "Prior year month total: $" . $pastMT['MT'];
    $string .= "</br>";
    $string .= "Month over month in dollars: $" . $monthOverMonthSub = $currentMTD['MTD'] - $pastMTD['MTD'];
    $string .= "</br>";
    $string .= "Month over month as percentage change: %" . round($monthOverMonthSub / $pastMTD['MTD'], 2);
    $string .= "</br>";
    $string .= "Goal for month to date: $" . $goalMonthToDate = $pastMTD['MTD'] * (1 + ($goal / 100));
    $string .= "</br>";
    $string .= "Goal for total month: $" . $pastMT['MT'] * (1 + ($goal / 100));
    $string .= "</br>";
    $string .= "Month to date goal perforamce in dollars: $" . $goalPerformaceMonthToDate = $currentMTD['MTD'] - $goalMonthToDate;
    $string .= "</br>";
    $string .= "Month to date goal perforamce as percentage difference: %" . round($goalPerformaceMonthToDate / $goalMonthToDate, 2);
    $string .= "</br>";
    $string .= "Current fyear to date: $" . $currentFYTD['FYTD'];
    $string .= "</br>";
    $string .= "Current fyear to date average transaction value: $" . $currentFYTD['FYTD'] / $currentFYTD['Transactions'];
    $string .= "</br>";
    $string .= "Prior fyear to date: $" . $pastFYTD['FYTD'];
    $string .= "</br>";
    $string .= "Prior fyear to date average transaction value: $" . $pastFYTD['FYTD'] / $pastFYTD['Transactions'];
    $string .= "</br>";
    $string .= "Year over year in dollars: $" . $yearOverMonthSub = $currentFYTD['FYTD'] - $pastFYTD['FYTD'];
    $string .= "</br>";
    $string .= "Year over year as percentage change: %" . round($yearOverMonthSub / $pastFYTD['FYTD'], 2);
    $string .= "</br>";
    $string .= "Goal for year to date: $" . $goalYearToDate = $pastFYTD['FYTD'] * (1 + ($goal / 100));
    $string .= "</br>";
    $string .= "Year to date goal perforamce in dollars: $" . $goalPerformaceYearToDate = $currentFYTD['FYTD'] - $goalYearToDate;
    $string .= "</br>";
    $string .= "Year to date goal perforamce as percentage difference: %" . round($goalPerformaceYearToDate / $goalYearToDate, 2);
    $string .= "</br>";

    return $string;
}

function explainDay_One($date, $location)
{
    $string = "";
    $date2 = date('Y-m-d', strtotime($date . '-1 year'));

    $currentMTD = fetchMTD($date, $location);
    $pastMTD = fetchMTD($date2, $location);
    $currentMT = fetchMonthTotal($date, $location);
    $pastMT = fetchMonthTotal($date2, $location);
    $currentFYTD = fetchFYTD($date, $location);
    $pastFYTD = fetchFYTD($date2, $location);
    $details = fetchAll($date, $location);
    $goal = 10;

    $string .= "<div class='display_section'>";
    $string .= "<span class='display_header'>Revenue</span></br>";

    $string .= "<div class='display_sub_section'>";
    $string .= "<span class='display_sub_header'>Day</span></br>";
    $string .= "<p class='key'>This date:</p> $" . $details['Actual_Pre_Tax_Intake'];
    $string .= "</br>";
    $day_goal = "Unknown";
    $string .= "<p class='key'>Daily Target:</p> $" . $day_goal;
    $string .= "</br>";
    $string .= "<p class='key'>Variance:</p> " . num($monthOverGoalSub = $details['Actual_Pre_Tax_Intake'] - $day_goal, TRUE, '$') . " | " . num($monthOverGoalSub / $day_goal, TRUE, '%');
    $string .= "</br>";
    $string .= "</div>"; //sub_section

    $string .= "<div class='display_sub_section'>";
    $string .= "<span class='display_sub_header'>Month</span></br>";
    $string .= "<p class='key'>MTD:</p> $" . $currentMTD['MTD'];
    $string .= "</br>";
    $string .= "<p class='key'>MoMTD:</p> $" . $pastMTD['MTD'];
    $string .= "</br>";
    $string .= "<p class='key'>Variance:</p> " . num($monthOverMonthSub = $currentMTD['MTD'] - $pastMTD['MTD'], TRUE, '$') . " | " . num($monthOverMonthSub / $pastMTD['MTD'], TRUE, '%');
    $string .= "</br>";
    $string .= "<p class='key'>MTD Goal:</p> $" . $goalMonthToDate = $pastMTD['MTD'] * (1 + ($goal / 100));
    $string .= "</br>";
    $string .= "<p class='key'>Variance:</p> " . num($goalPerformaceMonthToDate = $currentMTD['MTD'] - $goalMonthToDate) . " | " . num($goalPerformaceMonthToDate / $goalMonthToDate,TRUE, '%');
    $string .= "</br>";
    $string .= "</div>"; //sub_section

    $string .= "<div class='display_sub_section'>";
    $string .= "<span class='display_sub_header'>Year</span></br>";
    $string .= "<p class='key'>YTD:</p> $" . $currentFYTD['FYTD'];
    $string .= "</br>";
    $string .= "<p class='key'>YoYTD:</p> $" . $pastFYTD['FYTD'];
    $string .= "</br>";
    $string .= "<p class='key'>Variance:</p> " . num($yearOverMonthSub = $currentFYTD['FYTD'] - $pastFYTD['FYTD']) . " | " . num($yearOverMonthSub / $pastFYTD['FYTD'], TRUE, '%');
    $string .= "</br>";
    $string .= "<p class='key'>YTD Goal:</p> $" . $goalYearToDate = $pastFYTD['FYTD'] * (1 + ($goal / 100));
    $string .= "</br>";
    $string .= "<p class='key'>Variance:</p> " . num($goalPerformaceYearToDate = $currentFYTD['FYTD'] - $goalYearToDate) . " | " . num($goalPerformaceYearToDate / $goalYearToDate, TRUE, '%');
    $string .= "</br>";
    $string .= "</div>"; //sub_section

    $string .= "</div>"; // display_section

    $string .= "<div class='display_section'>";
    $string .= "<span class='display_header'>Transactions</span></br>";

    $string .= "<div class='display_sub_section'>";
    $string .= "<span class='display_sub_header'>Day</span></br>";
    $string .= "<p class='key'>This Date:</p> " . $details['TransCount'];
    $string .= "</br>";
    $string .= "<p class='key'>Avg. Value:</p> $" . $details['Actual_Pre_Tax_Intake'] / $details['TransCount'];
    $string .= "</br>";
    $string .= "</div>"; //sub_section

    $string .= "<div class='display_sub_section'>";
    $string .= "<span class='display_sub_header'>Month</span></br>";
    $string .= "<p class='key'>MTD:</p> " . $currentMTD['Transactions'];
    $string .= "</br>";
    $string .= "<p class='key'>MTD Average:</p> $" . $currentMTD['MTD'] / $currentMTD['Transactions'];
    $string .= "</br>";
    $string .= "</div>"; //sub_section

    $string .= "<div class='display_sub_section'>";
    $string .= "<span class='display_sub_header'>Year</span></br>";
    $string .= "<p class='key'>YTD:</p> " . $currentFYTD['Transactions'];
    $string .= "</br>";
    $string .= "<p class='key'>YTD Average:</p> $" . $currentFYTD['FYTD'] / $currentFYTD['Transactions'];
    $string .= "</br>";
    $string .= "</div>"; //sub_section

    $string .= "</div>"; // display_section

    $string .= "<div class='display_section'>";
    $string .= "<span class='display_header'>Details</span></br>";
    $string .= "<div class='display_sub_section'>";
    foreach ($details as $name => $detail){
        if(!empty($detail)) {
            $string .= $name . ": " . $detail;
            $string .= "</br>";
        }else{
            $var_empty = 1;
        }
    }
    if(isset($var_empty)){
        $string .= "Some details not found...";
        $string .= "</br>";
    }
    $string .= "</div>"; //sub_section
    $string .= "</div>"; // display_section
    return $string;
}





/* dataFetch()
	$detail -- Year View, Month View, or Day View
		Some data is only reachable at certain levels
	$startDate -- Point to Start -- Inlcusive
	$endDate -- Point to End -- Inclusive
		Date:
			- Dates must be given as valid date objects
				- They will be reduced or expanded as require_onced
	$location -- if not given the data will not be grouped by location and totals will be given
		- if Given it should be in text as shown in the location table
	$data -- the type of data to return
		- this will mostly be revenue, however, for daily data it could be anything entered

*/
//Error check not done

/* Chart Display -- Saved for later
function dataFetch($detail, $startDate, $endDate, $location, $data){
    global $con;
    $results = array(); // Return Variable
    $location = "%" . $location . "%";
    if($detail == "day"){
        //Dailies On Hold
        //Prepare
        if(!$stmt = $con->prepare("
			SELECT Name,`Date`, Revenue
			FROM RevenueDaily
			INNER JOIN Location
			ON RevDaily_idLocation = idLocation
			WHERE (`Date` BETWEEN ? AND ?)
			AND Name LIKE ?
			GROUP BY `Date`						
			")){
            echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        }
        //Bind
        if(!$stmt->bind_param("sss",$startDate,$endDate,$location)){
            echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Execute
        if(!$stmt->execute()){
            echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Bind Result
        if(!$stmt->bind_result($name,$date,$revenue)){
            echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
        }
        while($stmt->fetch()){
            $arr = array(
                'label' => $date,
                'value' => $revenue);
            //array_push($results,$arr); // Push the data to the results
            $results[] = $arr;
        }
        $stmt->close();
        return $results;

    }else if($detail == "month"){

        $startYear = date('Y',strtotime($startDate));
        $endYear = date('Y',strtotime($endDate));

        $startMonth = date('n',strtotime($startDate));
        $endMonth = date('n',strtotime($endDate));

        //Prepare
        if(!$stmt = $con->prepare("
			SELECT Name,`Year`,`Month`, SUM(Revenue)
			AS Revenue
			FROM RevenueMonthly
			INNER JOIN Location
			ON RevMonth_idLocation = idLocation
			WHERE (`Year` > ? AND `Year` < ?)
			OR (`Year` = ? AND `Month` >= ?)
			OR (`Year` = ? AND `Month` <= ?)
			AND Name LIKE ?
			GROUP BY `Year`,`Month`					
			")){
            echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        }
        //Bind
        if(!$stmt->bind_param("iiiiiis",
            $startYear,$endYear,
            $startYear,$startMonth,
            $endYear,$endMonth,
            $location)){
            echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Execute
        if(!$stmt->execute()){
            echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Bind Result
        if(!$stmt->bind_result($name,$year,$month,$revenue)){
            echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
        }
        while($stmt->fetch()){
            $arr = array(
                'label' => date("M-Y",mktime(0,0,0,$month,0,$year)),
                'value' => $revenue);
            //array_push($results,$arr); // Push the data to the results
            $results[] = $arr;
        }
        $stmt->close();
        return $results;

    }else if($detail == "year"){
        $startYear = date('Y',strtotime($startDate));
        $endYear = date('Y',strtotime($endDate));

        //Prepare
        if(!$stmt = $con->prepare("
			SELECT Name,`Year`, SUM(Revenue)
			AS Revenue
			FROM RevenueYearly
			INNER JOIN Location
			ON RevYear_idLocation = idLocation
			WHERE `Year`  BETWEEN ? AND ?
			AND Name LIKE ?
			GROUP BY `Year`					
			")){
            echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        }
        //Bind
        if(!$stmt->bind_param("sss",$startYear,$endYear,$location)){
            echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Execute
        if(!$stmt->execute()){
            echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Bind Result
        if(!$stmt->bind_result($name,$year,$revenue)){
            echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
        }
        while($stmt->fetch()){
            $arr = array(
                'label' => date("Y",mktime(0,0,0,0,0,$year)),
                'value' => $revenue);
            //array_push($results,$arr); // Push the data to the results
            $results[] = $arr;
        }
        $stmt->close();
        return $results;

    }

}


function dataDisplay($type,$data){
    $results["data"] = array();

    //Chart Build
    $results["chart"] = array(
        "numberPrefix"=>"$",
        "rotateValues" => "1",
        "placevaluesInside" => "1",
        "labelDisplay" => "1",
        "showValues" => "0",
        "valueFont" => "andale mono",
        "formatNumberScale" => "1",
        "labelStep" => "1",
        "valueAlpha" => "90",
        "canvasPadding" => "10",
        "theme" => "ocean",
        "exportenabled" => "1",
        "exportatclientside" => "1"
    );
    $results['data'] = $data;
    $json = json_encode($results);
    echo "<div class='chart-1' id='chart-1'></div>";

    //Convert Human Readable Chart Types to real type
    //Types: Column, Bar, Line, Area
    if($type == NULL){
        $type = "column2d";
    }
    $columnChart = new FusionCharts($type,"Revenue Chart", 800,300, "chart-1","json",$json);
    $columnChart->render();

}

/*
function dataDisplaySet($type,$data,$names){
	$results["dataset"] = array();
	$results['catagory'] = array();

	foreach(array_combine($names,$data) as $name => $set){
		$myData = array();
		$myData['seriesname'] = $name;
		$myData['data'] = $set;
		array_push($results["dataset"],$myData);
	}
	//Find Intersection
	$listOfCatagories = array();
	foreach($data as $set){
		foreach($set as $label){
			array_push($listOfCatagories,$label);
		}
	}
	var_dump($listOfCatagories);

	//Chart Build
	$results["chart"] = array(
			"numberPrefix"=>"$",
			"rotateValues" => "1",
			"placevaluesInside" => "1",
			"labelDisplay" => "1",
			"showValues" => "0",
			"valueFont" => "andale mono",
			"formatNumberScale" => "1",
			"labelStep" => "1",
			"valueAlpha" => "90",
			"canvasPadding" => "10",
			"theme" => "ocean",
			"exportenabled" => "1",
			"exportatclientside" => "1"
			);
	echo $json = json_encode($results);


	echo "<div class='chart-1' id='chart-1'></div>";

	//Convert Human Readable Chart Types to real type
	//Types: Column, Bar, Line, Area
	if($type == NULL){
		$type = "column2d";
	}
	$columnChart = new FusionCharts($type,"Revenue Chart", 800,300, "chart-1","json",$json);
	//$columnChart->render();

}

*/ // Chart Display Saved for later

//Functions for general data retrieval

/* Data retrieval Parameters
 * Location, Start Date, End Date
 *
 */

