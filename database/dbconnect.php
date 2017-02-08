<?php
function dbconnect()
{
    $server = "172.16.127.138";
    $user = "root";
    $pass = "root";
    $db = "Revenue";

    global $con;
    $con = new mysqli($server, $user, $pass, $db);
    if ($con->connect_error) {
        die("Connection Error:" . $con->connect_error);
    } else {

    }

    session_start();
}
dbconnect();
?>
