<?php
include_once '/var/www/html/database/dbconnect.php';

$MAX_LOGIN = 5; //Failed logins allowed Before lockout
$MAX_TIME = 1; //Time contraints for max logins (Hours) 

if(!isset($_POST['username']) || !isset($_POST['password'])){
	die("Expected Username and Password");
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
$retCount; // Number of login attempts
$currentDate = date('Y-m-d H:i:s');
$range = 60*60* $MAX_TIME; // 1 hour in seconds
$pastDate; //Current Date - time range
$pastDate = date('Y-m-d H:i:s',(strtotime($currentDate)-$range));

//Prepare
if(!$stmt = $con->prepare("SELECT COUNT(*) FROM (SELECT * FROM Authentication WHERE Auth_idUsers = (SELECT idUsers FROM Users WHERE Username = ?) AND Result = 0 AND `Date` BETWEEN ? AND ?) AS x")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Bind
if(!$stmt->bind_param("sss",$username,$pastDate,$currentDate)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($retCount)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}

while($stmt->fetch()){

}

$stmt->close();

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

//Should be a single line
while ($stmt->fetch()){
	if(password_verify($password,$hash)){
		$myUserId = $userid;
		echo "valid";	
		$_SESSION['username'] = $username;
		$_SESSION['level'] = $level;
		$_SESSION['location'] = $location;
		$_SESSION['hash'] = $hash;
		$_SESSION['role'] = $role;
		$_SESSION['group'] = array("m" => $m, "w" => $w, "r" => $r);
		$_SESSION['last_login'] = time(); //last time password was confirmed with DB
		$result = 1;
	}else{
		$result = 0;
		$myUserId = $userid;
	}
}
$stmt->close();

if($result == 0){
	echo "Username or Password Invalid";
}

/* We add here on success OR failure.*/

//Prepare
if(!$stmt = $con->prepare("INSERT INTO Authentication (Auth_idUsers,Date,IP,Result) VALUES (?,?,?,?)")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Bind
$datetime = date("Y-m-d H:i:s");
$remoteAddr = ip2long($_SERVER['REMOTE_ADDR']);
if(!$stmt->bind_param("isii",$myUserId,$datetime,$remoteAddr,$result)){
	echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->close();
 
