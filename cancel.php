<?php
require_once 'dbconnect.php';

require 'security.php'; //login check

if(!isset($_SESSION['submitdata'])){
	echo "Error no stored data";
}
unset($_SESSION['submitdata']);

echo "Entry Canceled";
