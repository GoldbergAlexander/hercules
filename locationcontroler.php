<?php 
require_once 'dbconnect.php';
require 'security.php';

if(!isset($_POST['submit'])){
	die("Invalid Usage");
}

//Standard Var check
if((!isset($_POST['name'])) || (strlen($_POST['name']) <= 0)){
	die("Invalid Usage");
}
if((!isset($_POST['address'])) || (strlen($_POST['address']) <= 0)){
	die("Invalid Usage");
}

if($_POST['submit'] == "update"){
	if((!isset($_POST['idlocation'])) || (strlen($_POST['idlocation']) <= 0)){
		die("Invalid Usage");
	}	
	$idlocation = htmlspecialchars($_POST['idlocation']);
	$name = htmlspecialchars($_POST['name']);
	$address = htmlspecialchars($_POST['address']);
	
	//Prepare
	if(!$stmt = $con->prepare("UPDATE Location SET Name=?,Address=? WHERE idLocation=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("ssi",$name,$address,$idlocation)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();
}
if($_POST['submit'] == "remove"){
	if((!isset($_POST['idlocation'])) || (strlen($_POST['idlocation']) <= 0)){
		die("Invalid Usage");
	}	
	$idlocation = htmlspecialchars($_POST['idlocation']);
	
	//Prepare
	if(!$stmt = $con->prepare("DELETE FROM Location WHERE idLocation=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("i",$idlocation)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();

}
if($_POST['submit'] == "create"){
	
	if(isset($_POST['idlocation'])){
		die("Invalid Usage");
	}	
	$name = htmlspecialchars($_POST['name']);
	$address = htmlspecialchars($_POST['address']);
	
	//Prepare
	if(!$stmt = $con->prepare("INSERT INTO Location (Name, Address) VALUES (?,?)")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("ss",$name,$address)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();


}

