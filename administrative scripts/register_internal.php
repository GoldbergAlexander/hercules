<?php
include_once "/var/www/html/database/dbconnect.php";
//Registration Function
/*
if(!isset($_POST['username']) || !isset($_POST['password'])){
	die("Expected Username and Password");
}
 */
$id; //DB defined
$username = "Test";//$_POST["username"]; //User Def
$password = "Test";//$_POST["password"]; //User Def
$hash; //System Def
$level; //Admin Def

$hash = password_hash($password,PASSWORD_DEFAULT);
$level = 0;
$username = mysqli_real_escape_string($con,$username);

//Prepare
if(!($stmt = $con->prepare("INSERT INTO Users (Username,Hash,level) VALUES (?,?,?)"))){
	echo "Prepare Failed: (" . $con->errno . ") "  . $stmt->error;
}
//Bind
if(!$stmt->bind_param("ssi",$username,$hash,$level)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ")" . $stmt->error;
}
$stmt->close();
?>
