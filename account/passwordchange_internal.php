<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
secure();

if(!isset($_POST['oldpass'])){
	die("Old Password Required");
}
$oldpass = $_POST['oldpass'];
if(strlen($oldpass) <= 0){
	die("Old Passoword Required");
}

if(!$stmt = $con->prepare("SELECT Hash FROM Users WHERE Username = ?")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Bind
if(!$stmt->bind_param("s",$_SESSION['username'])){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($hash)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}
//Should be a single line
while ($stmt->fetch()){
	if(password_verify($oldpass,$hash)){
		
	}else{
		die("Username or Password Invalid");
	
	}
}
$stmt->close();


if(!isset($_POST['pass']) || !isset($_POST['passconfirm'])){
	die("Password entries must match");
}

$pass = $_POST['pass'];
$passConfirm = $_POST['passconfirm'];

if($pass != $passConfirm){
	die("Password entries must match");
}
if(strlen($pass) <= 0){
	die("Password can not be blank");
}
if(strlen($pass) <= 8){
	die("Please enter a password nine (9) chars or longer");
}
$username = $_SESSION['username'];
$hash = password_hash($pass,PASSWORD_DEFAULT);

//Prepare
if(!$stmt = $con->prepare("UPDATE Users SET Hash = ? WHERE Username = ?")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Bind
if(!$stmt->bind_param("ss",$hash,$username)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->close();

echo "Password Updated";
