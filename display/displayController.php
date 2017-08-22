<?php
/**
 * Created by PhpStorm.
 * User: alexandergoldberg
 * Date: 4/30/17
 * Time: 4:38 PM
 */

require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/display/displayLibrary.php';
secure();


/** retrieve sent post information and update session for it */

$_SESSION['display'] = array();

$location = filterString($_POST['location']);
$date = filterDate($_POST['date']);

$_SESSION['display']['location'] = $location;
$_SESSION['display']['date'] = $date;

