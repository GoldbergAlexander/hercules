<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php'; //login check
secure();

if(!isset($_SESSION['submitdata'])){
	echo "Error no stored data";
}
unset($_SESSION['submitdata']);

echo "Entry Canceled";
