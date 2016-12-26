<?php
require_once 'dbconnect.php';
require 'security.php';

/*outline*/

//get users

//get locations

//show users 
	//id -- Locked
	//username -- mutable
	//password -- resetable (hash not retrieved)
		//if reset, password is displayed but not cached
	//level -- mutable
	//group -- mutable (from selection)
	//location -- mutable (from selection)


//Create new user
	//username -- entered
	//password -- generated (same action as reset)
	//level -- entered
	//group -- selected
	//location -- selected


//DB Work

//Get locations

//Prepare
if(!$stmt = $con->prepare("SELECT Name FROM Location ORDER BY Name ASC")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($name)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}
$locations = array();
while($stmt->fetch()){
	$locations[] = $name;
}
$stmt->close();

//Get Rolesi
//Prepare
if(!$stmt = $con->prepare("SELECT Role FROM `Group` ORDER BY Role ASC")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($name)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}
$groups = array();
while($stmt->fetch()){
	$groups[] = $name;
}
$stmt->close();



//Get Users

//Prepare
if(!$stmt = $con->prepare("SELECT idUsers, Username, Level,Name,Role 
			FROM Users 
			LEFT JOIN Location 
			ON Users_idLocation=idLocation 
			LEFT JOIN `Group`
			ON Users_idGroup = idGroup
			ORDER BY Username ASC")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}

//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($idUser,$Username,$Level,$location,$role)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}

echo "<div class='usermanagment' id='usermanagment'>";
echo "<div class='usermanagmentlabels' id='usermanagmentlabels'>";
	echo "<div>Username</div>";
	echo "<div class='labellevel'>Level</div>";
	echo "<div class='labelselect'>Role</div>";
	echo "<div class='labelselect'>Location</div>";
echo "</div>"; //usermanagmentlabels

while($stmt->fetch()){
	echo "<div class='user' id='user'>";
	echo "<form class='userform' id='userform'>";
	
	echo "<input type='hidden' name='iduser' value='$idUser'>";
	echo "<input type='text' name='username' value='$Username'>";
	echo "<input type='number' class='inputlevel' name='level' value='$Level'>";
	echo "<select name='group'>";
		echo "<option value='$role' selected>$role</option>";
	foreach($groups as $item){
		echo "<option value='$item'>$item</option>";
	}
	echo "</select>";
	echo "<select name='location'>";
		echo "<option value='$location' selected>$location</option>";
	foreach($locations as $item){
		echo "<option value='$item'>$item</option>";
	}
	echo "</select>";
	echo "<input type='hidden' name='password'>";
	echo "<input class='ulbutton' type='submit' value='Password Reset' name='password'>";
	echo "<input class='ulbutton' type='submit' value='Remove' name='remove'>";
	echo "<input class='ulbutton' type='submit' value='Update' name='update'>";
	echo "</form>"; //userform
	echo "</div>";//user
}

//Add user option
echo "<div class='user' id='user'>";
	echo "<form class='userform' id='userform'>";

	echo "<input type='text' name='username'>";
	echo "<input type='number' class='inputlevel' name='level'>";
	echo "<select name='group'>";
	foreach($groups as $item){
		echo "<option value='$item'>$item</option>";
	}
	echo "</select>";

	echo "<select name='location'>";
	foreach($locations as $item){
		echo "<option value='$item'>$item</option>";
	}
	echo "</select>";
	echo "<input type='hidden' name='password'>";
	echo "<input class='ulbutton' type='submit' value='Create' name='create'>";
	echo "</form>"; //userform
	echo "</div>";//user

echo "</div>"; //usermanagmet
$stmt->close();

