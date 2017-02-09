<?php 
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/management/managementLibrary.php';
secure();


if(!groupUsage()){
    die("Invalid Usage");
}

switch ($_POST['submit']) {
    case "update":
        if (!updateGroup($con)) {
        echo "Error Updating Group";
        }
        else {
            echo "Group Updated";
        }
        break;

    case "remove":
        if (!removeGroup($con)) {
            echo "Error Removing Group";
        } else {
            echo "Group Removed";
        }
        break;

    case "create":
        if (!createGroup($con)) {
            echo "Error Creating Group";
        } else {
            echo "Group Created";
        }
        break;

    default:
        echo "Invalid Usage";
}

