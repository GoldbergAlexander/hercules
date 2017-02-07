<?php

function secure($checkStrength = NULL, $checkLevel = NULL, $checkGroup = NULL, $checkPermissions = NULL){
	//NULL Strength
	//General Check
	//Ensure all session variables are set, regarless of their value
	if(!isset($_SESSION['username'])){
		myAbort();
	}
	if(!isset($_SESSION['level'])){
		myAbort();
	}
	if(!isset($_SESSION['location'])){
		myAbort();
	}
	if(!isset($_SESSION['hash'])){
		myAbort();
	}
	if(!isset($_SESSION['role'])){
		myAbort();
	}
	if(!isset($_SESSION['group'])){
		myAbort();
	}
	if(!isset($_SESSION['last_login'])){
		myAbort();
	}
	
	//At levels over 0, secure will check other args for reqs

}
function myAbort(){
	header('HTTP/1.0 403 Forbidden');
	die("Please Login");
}

