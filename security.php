<?php
if(!isset($_SESSION['username'])){
	header('HTTP/1.0 403 Forbidden');
	die("Please Login");
}
if(!isset($_SESSION['level'])){
	header('HTTP/1.0 403 Forbidden');
	die("Please Login");
}
if($_SESSION['level'] < 0){
	header('HTTP/1.0 403 Forbidden');
	die("Invalid Access");
}
