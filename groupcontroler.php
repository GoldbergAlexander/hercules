<?php 
require_once 'dbconnect.php';
require 'security.php';

if(!isset($_POST['submit'])){
	die("Invalid Usage");
}
//idgroup -- int
//role -- text
//managment -- bool
//read -- bool
//write -- bool


//Standard Var check
if((!isset($_POST['role'])) || (strlen($_POST['role']) <= 0)){
	die("Invalid Usage");
}

if($_POST['submit'] == "update"){
	if((!isset($_POST['idgroup'])) || (strlen($_POST['idgroup']) <= 0)){
		die("Invalid Usage");
	}	
	$idgroup = htmlspecialchars($_POST['idgroup']);
	$role = htmlspecialchars($_POST['role']);
	$mgmt = 0;
	$read = 0;
	$write = 0;

	if(isset($_POST['managment'])){
		$mgmt = 1;
	}
	if(isset($_POST['read'])){
		$read = 1;
	}
	if(isset($_POST['write'])){
		$write = 1;
	}
	
	//Prepare
	if(!$stmt = $con->prepare("UPDATE `Group` 
				SET `Role`=?,`Managment`=?,`Read`=?,`Write`=? 
				WHERE idGroup=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("siiii",$role,$mgmt,$read,$write,$idgroup)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();
}
if($_POST['submit'] == "remove"){
	if((!isset($_POST['idgroup'])) || (strlen($_POST['idgroup']) <= 0)){
		die("Invalid Usage");
	}	
	$idgroup = htmlspecialchars($_POST['idgroup']);
	
	//Prepare
	if(!$stmt = $con->prepare("DELETE FROM `Group` WHERE idGroup=?")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("i",$idgroup)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();

}
if($_POST['submit'] == "create"){
	if(isset($_POST['idgroup'])){
		die("Invalid Usage");
	}	
	$role = htmlspecialchars($_POST['role']);
	$mgmt = 0;
	$read = 0;
	$write = 0;

	if(isset($_POST['managment'])){
		$mgmt = 1;
	}
	if(isset($_POST['read'])){
		$read = 1;
	}
	if(isset($_POST['write'])){
		$write = 1;
	}
	
	//Prepare
	if(!$stmt = $con->prepare("INSERT INTO `Group` (`Role`,`Managment`,`Read`,`Write`)
				VALUES(?,?,?,?)")){
		echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
	}	
	//Bind
	if(!$stmt->bind_param("siii",$role,$mgmt,$read,$write)){
		echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	//Execute
	if(!$stmt->execute()){
		echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();

}

