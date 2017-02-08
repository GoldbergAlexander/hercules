<?php
include_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php'; //login check
require_once '/var/www/html/entry/entryLibrary.php';
secure();

echo "<div class='entryrec'>";

echo insertEntry($con);
//Get the last entry id
$entryid = $con->insert_id;
echo insertMemo($con, $entryid);

//Set Confirmation
$IP = $_SERVER['REMOTE_ADDR'];
$IP = ip2long($IP);
$userid = getUserId($con);
$datetime = date('Y-m-d H:i:s');
echo insertConfirmation($con, $entryid, $userid, $datetime, $IP);

echo "</div>";


