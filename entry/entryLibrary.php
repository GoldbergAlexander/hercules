<?php
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

/**
 * @param $con
 */
function getDepartments($con)
{
//Get Department List
    $sql = "SELECT Name FROM Department";
    $departmentResults = $con->query($sql);
    if (!$departmentResults) {
        echo "Department Query Error";
        return $departmentResults;
    }
    return $departmentResults;
}

/**
 * @return false|int|string
 * Set the day back by one day if a workday, if its monday, send it back two days
 */
function offsetDate()
{
    $date = time();
    if (date("l", $date) == "Monday") {
        $date = $date - 2 * 60 * 60 * 24;
        $date = date("Y-m-d", $date);
        return $date;
    } else {
        $date = $date - 60 * 60 * 24;
        $date = date("Y-m-d", $date);
        return $date;
    }
}

/**
 * @param $locations
 * @param $departments
 * @param $date
 */
function form($date, $locations)
{
    $string = "";
    if (isset($_SESSION['submitdata'])) {
        $array = $_SESSION['submitdata'];
        $date = $array['date'];
        $location = $array['location'];
    }

    $string .= "<div class='entryform'>";
    $string .= "<form autocomplete='off'id='entryform'>";

    $string .= "<div class='entryforminput'>";
    if (!empty($locations)) {
        $string .= "<div>Location</div>";
        $string .= "<select name='location'>";
        if (isset($location)) {
            $string .= "<option value='" . $location . "'>" . $location . "</option>";
        } else {
            $string .= "<option value='" . $_SESSION['location'] . "'>" . $_SESSION['location'] . "</option>";
        }
        while ($row = $locations->fetch_assoc()) {
            $string .= "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
        }
        $string .= "</select>";
        $string .= "</div>";
    }



    $string .= "<div class='entryforminput'>";
    $string .= "<div>Date</div>";
    $string .= "<input type='date' name='date' value='" . $date . "'>";
    $string .= "</div>";

    if (!isset($array['transcount'])) {
        $array['transcount'] = 0;
    }

    $string .= "<div class='entryforminput'>";
    $string .= "<div>Transaction Count</div>";
    $string .= "<input type='text' name='transcount' value='" . $array['transcount'] . "'>";
    $string .= "</div>";

    if (!isset($array['cashcount'])) {
        $array['cashcount'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Cash Count</div>";
    $string .= "<input type='text' name='cashcount' value='" . $array['cashcount'] . "'>";
    $string .= "</div>";

    if (!isset($array['checkcount'])) {
        $array['checkcount'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Check Count</div>";
    $string .= "<input type='text' name='checkcount' value='" . $array['checkcount'] . "'>";
    $string .= "</div>";

    if (!isset($array['payout'])) {
        $array['payout'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Payout</div>";
    $string .= "<input type='text' name='payout'value='" . $array['payout'] . "'>";
    $string .= "</div>";

    if (!isset($array['cardunit'])) {
        $array['cardunit'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Card Unit</div>";
    $string .= "<input type='text' name='cardunit'value='" . $array['cardunit'] . "'>";
    $string .= "</div>";

    if (!isset($array['cashtape'])) {
        $array['cashtape'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Cash Tape</div>";
    $string .= "<input type='text' name='cashtape'value='" . $array['cashtape'] . "'>";
    $string .= "</div>";

    if (!isset($array['checktape'])) {
        $array['checktape'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Check Tape</div>";
    $string .= "<input type='text' name='checktape'value='" . $array['checktape'] . "'>";
    $string .= "</div>";

    if (!isset($array['cardtape'])) {
        $array['cardtape'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Card Tape</div>";
    $string .= "<input type='text' name='cardtape'value='" . $array['cardtape'] . "'>";
    $string .= "</div>";

    if (!isset($array['taxtape'])) {
        $array['taxtape'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Tax Tape</div>";
    $string .= "<input type='text' name='taxtape'value='" . $array['taxtape'] . "'>";
    $string .= "</div>";

    if (!isset($array['salesvoid'])) {
        $array['salesvoid'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Sales Void</div>";
    $string .= "<input type='text' name='salesvoid'value='" . $array['salesvoid'] . "'>";
    $string .= "</div>";

    if (!isset($array['taxvoid'])) {
        $array['taxvoid'] = 0.00;
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Tax Void</div>";
    $string .= "<input type='text' name='taxvoid'value='" . $array['taxvoid'] . "'>";
    $string .= "</div>";

    if (!isset($array['memo'])) {
        $array['memo'] = "";
    }
    $string .= "<div class='entryforminput'>";
    $string .= "<div>Memo</div>";
    $string .= "<input type='textarea' name='memo' value='" . $array['memo'] . "'>";
    $string .= "</div>";

    $string .= "<div class='entryforminput'>";
    $string .= "<input class='efsubmit' type='submit' name='submit' value='Review'>";
    $string .= "</div>";

    $string .= "</form>";
    $string .= "</div>";

    return $string;
}

/**
 * @param $con
 * @param $department
 * @return string
 */
function departmentToId($con, $department)
{
//convert departmetn to id
    $departmentid = "";
//prepare
    if (!$stmt = $con->prepare("SELECT idDepartment FROM Department WHERE Name=? LIMIT 1")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//bind
    if (!$stmt->bind_param("s", $department)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//bind results
//fetch
    $stmt->bind_result($departmentid);
    while ($stmt->fetch()) {

    }
    $stmt->close();
    return $departmentid;
}

/**
 * @param $con
 * @param $location
 * @return string
 */
function locationToId($con, $location)
{
    $locationid = "";
    if (!$stmt = $con->prepare("SELECT idLocation FROM Location WHERE Name=? LIMIT 1")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//bind
    if (!$stmt->bind_param("s", $location)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//bind results
    $stmt->bind_result($locationid);
    while ($stmt->fetch()) {

    }
    $stmt->close();
    return $locationid;
}

function filterInt($input){
    return filter_var($input,FILTER_SANITIZE_NUMBER_INT);
}
function filterFloat($input){
    return filter_var($input,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
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


function entryReview()
{
    $string = "";
    if (!isset($_SESSION['submitdata'])) {
        die("Error no data stored");
    }

    $array = $_SESSION['submitdata'];
//Display
    $string .= "<div class='review'>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Location</div>";
    $string .= "<div class='item'>" . $array['location'] . "</div>";
    $string .= "</div>";


    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Date</div>";
    $string .= "<div class='item'>" . $array['date'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Transaction Count</div>";
    $string .= "<div class='item'>" . $array['transcount'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Cash Count</div>";
    $string .= "<div class='item'>" . $array['cashcount'] . "</div>";

    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Check Count</div>";
    $string .= "<div class='item'>" . $array['checkcount'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Payout</div>";
    $string .= "<div class='item'>" . $array['payout'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Card Unit</div>";
    $string .= "<div class='item'>" . $array['cardunit'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Cash Tape</div>";
    $string .= "<div class='item'>" . $array['cashtape'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Check Tape</div>";
    $string .= "<div class='item'>" . $array['checktape'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Card Tape</div>";
    $string .= "<div class='item'>" . $array['cardtape'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Tax Tape</div>";
    $string .= "<div class='item'>" . $array['taxtape'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Sales Void</div>";
    $string .= "<div class='item'>" . $array['salesvoid'] . "</div>";
    $string .= "</div>";

    $string .= "<div class='reviewoutput'>";
    $string .= "<div>Tax Void</div>";
    $string .= "<div class='item'>" . $array['taxvoid'] . "</div>";
    $string .= "</div>";

    if ($array['memo'] != "") {
        $string .= "<div class='reviewoutput'>";
        $string .= "<div>Memo</div>";
        $string .= "<div class='item'>" . $array['memo'] . "</div>";
        $string .= "</div>";
    }

    $string .= "</div>";

    $string .= "<div class='reviewdo'>";

    $string .= "<form id='submitdata'>";
    $string .= "<input type='submit' name='submit' value='Submit'>";
    $string .= "</form>";

    $string .= "<form id='backentry'>";
    $string .= "<input type='submit' name='submit' value='Modify'>";
    $string .= "</form>";

    $string .= "<form id='cancelsubmit'>";
    $string .= "<input type='submit' name='submit' value='Reset'>";
    $string .= "</form>";

    $string .= "</div>";

    return $string;
}


/**
 * @return string
 */
function entryClear()
{
    $string = "";
    if (!isset($_SESSION['submitdata'])) {
        $string .= "Error no stored data";
    }
    unset($_SESSION['submitdata']);

    $string .= "Entry Canceled";
    return $string;
}

/**
 * @param $con
 * @return mixed
 */
function insertEntry($con)
{
    $string = "";
    if (!isset($_SESSION['submitdata'])) {
        die("Error no stored Data");
    }

    $array = $_SESSION['submitdata'];

    $location = $array['location'];
    $locationid = $array['locationid'];
    $date = $array['date'];
    $transcount = $array['transcount'];
    $cashcount = $array['cashcount'];
    $checkcount = $array['checkcount'];
    $payout = $array['payout'];
    $cardunit = $array['cardunit'];
    $cashtape = $array['cashtape'];
    $checktape = $array['checktape'];
    $cardtape = $array['cardtape'];
    $taxtape = $array['taxtape'];
    $salesvoid = $array['salesvoid'];
    $taxvoid = $array['taxvoid'];


//Prepare
    if (!$stmt = $con->prepare("INSERT INTO DailyRevenueEntry 
                                          (
                                          DailyRevEntry_idLocation,
                                          Date,
                                          TransCount, 
                                          CashCount,
                                          CheckCount,
                                          PayoutReceipt,
                                          CardUnit,
                                          CashTape,
                                          CheckTape,
                                          CardTape,
                                          TaxTape,
                                          SalesVoid,
                                          TaxVoid
                                          ) VALUES (
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?, 
                                          ?)")
    ) {
        $string .= "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }

//Add ablity for array handling
//Bind
    if (!$stmt->bind_param("issssssssssss",
        $locationid,
        $date,
        $transcount,
        $cashcount,
        $checkcount,
        $payout,
        $cardunit,
        $cashtape,
        $checktape,
        $cardtape,
        $taxtape,
        $salesvoid,
        $taxvoid)
    ) {
        $string .= "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }

//Execute
    if (!$stmt->execute()) {
        $string .= "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
    return $string;
}

/**
 * @param $con
 * @return int
 */

function getUserId($con)
{
    $userid = "";

//Get user id

//prepare
    if (!$stmt = $con->prepare("SELECT idUsers 
                            FROM Users 
                            WHERE Username=? 
                            LIMIT 1")
    ) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//bind
    if (!$stmt->bind_param("s", $_SESSION['username'])) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }

//bind results
//fetch
    $stmt->bind_result($userid);
    while ($stmt->fetch()) {

    }
    $stmt->close();
    return $userid;
}

/**
 * @param $con
 * @param $entryid
 * @param $userid
 * @param $datetime
 * @param $IP
 * @return string
 */
function insertConfirmation($con, $entryid, $userid, $datetime, $IP)
{
//Insert Confirmation
//prepare
    $string = "";
    if (!$stmt = $con->prepare("INSERT INTO Confirmation 
                                        (
                                        Con_idDailyRevenueEntry,
                                        Con_idUsers,
                                        Datetime,
                                        IP
                                        ) VALUES (
                                        ?,
                                        ?,
                                        ?,
                                        ?)")
    ) {
        $string .= "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//bind
    if (!$stmt->bind_param("iiss", $entryid, $userid, $datetime, $IP)) {
        $string .= "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//execute
    if (!$stmt->execute()) {
        $string .= "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
    return $string;
}


/**
 * @param $con
 * @param $entryid
 */
function insertMemo($con, $entryid)
{
    $string = "";
    $memo = $_SESSION['submitdata']['memo'];
    if ($memo != '') {
        //prepare
        if (!$stmt = $con->prepare("INSERT INTO Memo 
                                            (Memo_idDailyRevEntry,
                                            Data) VALUES (
                                            ?,
                                            ?)")
        ) {
            $string .= "Prepare Failed: (" . $con->errno . ") " . $con->error;
        }
        //bind
        if (!$stmt->bind_param("is", $entryid, $memo)) {
            $string .= "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        //execute
        if (!$stmt->execute()) {
            $string .= "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
    }
    $string .= "The data has been saved";
    return $string;
}