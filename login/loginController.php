<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/login/loginLibrary.php';

$MAX_LOGIN = 5; //Failed logins allowed Before lockout
$MAX_TIME = 1; //Time constraints for max login' (Hours)


if (!loginUsage()){
    die();
}

$username = $_POST['username'];
$password = $_POST['password'];
$hash = NULL;
$level = NULL;
$role = NULL;

//Sanatize
$username = mysqli_real_escape_string($con,$username);

/* Account lockout system 
 * as it stands, this system only locks out specific accounts
 * It does not block by IP address, in the event all users use the same intranet
 * It also does not performe any caching, so even when an account is locked,
 * the db is still checked for recent logins.
 * Becouse the system informs users when their account has been locked, this is an information leek
 * An attacker could use the system to find the usernames by locking them out.
 */

//Get number of failed login attempts in given time
$retCount = ""; // Number of login attempts
$currentDate = date('Y-m-d H:i:s');
$range = 60 * 60 * $MAX_TIME; // 1 hour in seconds
$pastDate = date('Y-m-d H:i:s', strtotime($currentDate) - $range);


$retCount = failedLogins($con, $username, $pastDate, $currentDate, $retCount);

if($retCount >= $MAX_LOGIN){
	die("Account Locked");
}


//Continue Login

//Prepare
if(!$stmt = $con->prepare("SELECT idUsers, Username, Hash, level, Name, Role, Managment, `Write`, `Read` 
	FROM Users 
	LEFT JOIN Location 
	ON Users_idLocation = idLocation
	LEFT JOIN
	`Group`
	ON Users_idGroup = idGroup 
	WHERE Username = ? ")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Bind
if(!$stmt->bind_param("s",$username)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($userid,$username,$hash,$level,$location,$role,$m,$w,$r)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}

//Vars for authentication table
$myUserId; //Used for adding transaction 
$result = 0; //Used to see if nothing is returned

while ($stmt->fetch()){
	if(password_verify($password,$hash)){
		$myUserId = $userid;
        $result = login($username, $level, $location, $hash, $role, $m, $w, $r);
	}else{
		$result = 0;
		$myUserId = $userid;
	}
}
$stmt->close();

if($result == 0){
	echo "Username or Password Invalid";
}

if(!registerAttempt($con, $myUserId, $result)){
    echo "Unable to Register Attempt";
}
 
