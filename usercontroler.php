<?php 
require_once 'dbconnect.php';
require 'security.php';

/* Security note
 * currently we use the common db connection. In the future it may be wise to use a seperate
 * DB account for this admin work. Currently, this is the only activity that would allow for 
 * UPDATE and DELETE rights
 */

/* possible inputs from form
 * reset,remove,update,create
 * for all actions check for the id,
 * 	if its not set it is a create action
 * 	if it is set, but it doesn't match existing, return an error
 * 	if it is set and matches, continue the update
 *
 * Password Reset
 * 	New Pass is generate in the client and sent to the server
 * 		Set in the hidden element
 * 	Password is generated in crypto and entered into the db
 * 	Notify client on success but don't return the hash.
 *
 * Update
 * 	Username, level and location are check for validity and entered into db
 * 	Password is left unchanged
 *
 * Remove
 * 	User is removed from the table
 * 	This functionality may be removed
 * 
 * Create
 * 	Password is generated by client
 * 	All values are checked and stored as a new user  
 */

// ID Checking

if(!isset($_POST['submit'])){
	die("Invalid Usage");
}

//Standard Var check
if((!isset($_POST['username'])) || (strlen($_POST['username']) <= 0)){
	die("Invalid Usage");
}
if((!isset($_POST['level'])) || (strlen($_POST['level']) <= 0)){
	die("Invalid Usage");
}
if((!isset($_POST['group'])) || (strlen($_POST['group']) <= 0)){
	die("Invalid Usage");
}
if((!isset($_POST['location'])) || (strlen($_POST['location']) <= 0)){
	die("Invalid Usage");
}

if($_POST['submit'] == "password"){
	//CHeck for a password and make sure its not null
	if(!isset($_POST['password']) || (strlen($_POST['password']) <= 0)){
		die("Invalid Usage");
	}
	$iduser = htmlspecialchars($_POST['iduser']);
	$pass = $_POST['password'];
	echo "Setting $pass as new password.";
	$hash = password_hash($pass, PASSWORD_DEFAULT); 
	
	//Prepare
	if(!$stmt = $con->prepare("UPDATE Users SET Hash=? WHERE idUsers=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("si",$hash,$iduser)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$stmt->close();
	
}
if($_POST['submit'] == "update"){
	$iduser = htmlspecialchars($_POST['iduser']);
	$username = htmlspecialchars($_POST['username']);
	$location = htmlspecialchars($_POST['location']);
	$group = htmlspecialchars($_POST['group']);
	$level = htmlspecialchars($_POST['level']);
	//Prepare
	if(!$stmt = $con->prepare("UPDATE Users SET Username=?,Level=?
		,Users_idGroup=
		(SELECT idGroup FROM `Group` WHERE Role=?)
		,Users_idLocation=
		(SELECT idLocation FROM Location WHERE Name=?) WHERE idUsers=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("sissi",$username,$level,$group,$location,$iduser)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();
}
if($_POST['submit'] == "remove"){
	$iduser = htmlspecialchars($_POST['iduser']);
	//Prepare
	if(!$stmt = $con->prepare("DELETE FROM Users WHERE idUsers=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("i",$iduser)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();
}
if($_POST['submit'] == "create"){
	//make sure an ID does not exist in the submission 
	if(isset($_POST['iduser'])){
		die("Invalid Usage");
	}
	
	$username = htmlspecialchars($_POST['username']);
	$location = htmlspecialchars($_POST['location']);
	$group = htmlspecialchars($_POST['group']);
	$level = htmlspecialchars($_POST['level']);
	$pass = $_POST['password'];
	$hash = password_hash($pass, PASSWORD_DEFAULT); 
	//Prepare
	if(!$stmt = $con->prepare("INSERT INTO Users 
		(Username,Level,Hash,Users_idGroup,Users_idLocation) 
		VALUES (?,?,?,
		(SELECT idGroup FROM `Group` WHERE Role=?),
		(SELECT idLocation FROM Location WHERE Name=?))")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("sisss",$username,$level,$hash,$group,$location)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();

	echo "User $username Created with Password $pass";


}

