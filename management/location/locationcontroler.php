<?php 
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/management/managementLibrary.php';

secure();

if (!locationUsage()){
    die();
}

switch ($_POST['submit']) {
    case "update":
        if (!updateLocation($con)) {
        echo "Error Updating Location";
        }
        else {
            echo "Location Update Saved";
        }
        break;

    case "remove":
        if (!removeLocation($con)) {
            echo "Error Removing Location";
        } else {
            echo "Location Removed";
        }
        break;
    case "create":
        if (!createLocation($con)) {
            echo "Error Creating Location";
        } else {
            echo "Location Created";
        }
        break;

    default:
        die("Invalid Usage");
        break;

}

