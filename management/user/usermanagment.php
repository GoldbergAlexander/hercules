<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/management/managementLibrary.php';
secure();
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

echo "<div class='usermanagment' id='usermanagment'>";
$locations = getLocations($con);
$groups = getGroups($con);
echo getUsers($con, $groups, $locations);
echo displayCreateUser($groups, $locations);
echo "</div>"; //usermanagmet