<?php
/**
 * User Control Functions
 */

/**
 * @return bool
 */
function usageUser()
{
    if (!isset($_POST['submit'])) {
        echo("Invalid Usage");
        return false;
    }
    //Standard Var check
    if ((!isset($_POST['username'])) || (strlen($_POST['username']) <= 0)) {
        echo("Invalid Usage");
        return false;
    }
    if ((!isset($_POST['level'])) || (strlen($_POST['level']) <= 0)) {
        echo("Invalid Usage");
        return false;
    }
    if ((!isset($_POST['group'])) || (strlen($_POST['group']) <= 0)) {
        echo("Invalid Usage");
        return false;
    }
    if ((!isset($_POST['location'])) || (strlen($_POST['location']) <= 0)) {
        echo("Invalid Usage");
        return false;
    }

    return true;
}

/**
 * @param $con
 * @param $hash
 * @param $iduser
 * @return bool
 */
function updatePassword($con, $hash, $iduser)
{
//Prepare
    if (!$stmt = $con->prepare("UPDATE Users SET Hash=? WHERE idUsers=?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("si", $hash, $iduser)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    $stmt->close();
    return true;
}

/**
 * @param $con
 * @param $username
 * @param $level
 * @param $group
 * @param $location
 * @param $iduser
 * @return bool
 */
function updateUser($con, $username, $level, $group, $location, $iduser)
{
//Prepare
    if (!$stmt = $con->prepare("UPDATE Users SET Username=?,Level=?
		,Users_idGroup=
		(SELECT idGroup FROM `Group` WHERE Role=?)
		,Users_idLocation=
		(SELECT idLocation FROM Location WHERE Name=?) WHERE idUsers=?")
    ) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("sissi", $username, $level, $group, $location, $iduser)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}


/**
 * @param $con
 * @param $iduser
 */
function removeUser($con, $iduser)
{
//Prepare
    if (!$stmt = $con->prepare("DELETE FROM Users WHERE idUsers=?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("i", $iduser)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}

/**
 * @param $con
 * @param $username
 * @param $level
 * @param $hash
 * @param $group
 * @param $location
 */
function insertUser($con, $username, $level, $hash, $group, $location)
{
//Prepare
    if (!$stmt = $con->prepare("INSERT INTO Users 
		(Username,Level,Hash,Users_idGroup,Users_idLocation) 
		VALUES (?,?,?,
		(SELECT idGroup FROM `Group` WHERE Role=?),
		(SELECT idLocation FROM Location WHERE Name=?))")
    ) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("sisss", $username, $level, $hash, $group, $location)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}

/**
 * User Management Functions
 */

/**
 * @param $con
 * @param $name
 * @return array
 */
function getLocations($con)
{
    $name = "";
    if (!$stmt = $con->prepare("SELECT Name FROM Location ORDER BY Name ASC")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind
    if (!$stmt->bind_result($name)) {
        echo "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $locations = array();
    while ($stmt->fetch()) {
        $locations[] = $name;
    }
    $stmt->close();
    return $locations;
}

/**
 * @param $con
 * @param $name
 * @return array
 */
function getGroups($con)
{
    $name = "";
    if (!$stmt = $con->prepare("SELECT Role FROM `Group` ORDER BY Role ASC")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind
    if (!$stmt->bind_result($name)) {
        echo "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $groups = array();
    while ($stmt->fetch()) {
        $groups[] = $name;
    }
    $stmt->close();
    return $groups;
}

/**
 * @param $idUser
 * @param $Username
 * @param $Level
 * @param $role
 * @param $groups
 * @param $location
 * @param $locations
 * @return $string
 */
function showUser($idUser, $Username, $Level, $role, $groups, $location, $locations)
{
    $string = "";
    $string .= "<div class='user' id='user'>";
    $string .= "<form class='userform' id='userform'>";

    $string .= "<input type='hidden' name='iduser' value='$idUser'>";
    $string .= "<input type='text' name='username' value='$Username'>";
    $string .= "<input type='number' class='inputlevel' name='level' value='$Level'>";
    $string .= "<select name='group'>";
    $string .= "<option value='$role' selected>$role</option>";
    foreach ($groups as $item) {
        $string .= "<option value='$item'>$item</option>";
    }
    $string .= "</select>";
    $string .= "<select name='location'>";
    $string .= "<option value='$location' selected>$location</option>";
    foreach ($locations as $item) {
        $string .= "<option value='$item'>$item</option>";
    }
    $string .= "</select>";
    $string .= "<input type='hidden' name='password'>";
    $string .= "<input class='ulbutton' type='submit' value='Password Reset' name='password'>";
    $string .= "<input class='ulbutton' type='submit' value='Remove' name='remove'>";
    $string .= "<input class='ulbutton' type='submit' value='Update' name='update'>";
    $string .= "</form>"; //userform
    $string .= "</div>";
    return $string;//user
}

function userLabels()
{
    $string = "";
    $string .=  "<div class='usermanagmentlabels' id='usermanagmentlabels'>";
    $string .=  "<div>Username</div>";
    $string .=  "<div class='labellevel'>Level</div>";
    $string .=  "<div class='labelselect'>Role</div>";
    $string .=  "<div class='labelselect'>Location</div>";
    $string .=  "</div>"; //usermanagmentlabels
    return $string;
}

/**
 * @param $con
 * @param $groups
 * @param $locations
 */
function getUsers($con, $groups, $locations)
{
    $string = "";
    //Get Users
    $idUser = "";
    $Username = "";
    $Level = "";
    $role = "";
    $location = "";
    //Prepare
    if (!$stmt = $con->prepare("SELECT idUsers, Username, Level,Name,Role 
			FROM Users 
			LEFT JOIN Location 
			ON Users_idLocation=idLocation 
			LEFT JOIN `Group`
			ON Users_idGroup = idGroup
			ORDER BY Username ASC")
    ) {
        $string .=  "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
    //Execute
    if (!$stmt->execute()) {
        $string .=  "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    //Bind
    if (!$stmt->bind_result($idUser, $Username, $Level, $location, $role)) {
        $string .=  "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $string .=  userLabels();
    while ($stmt->fetch()) {
        $string .=  showUser($idUser, $Username, $Level, $role, $groups, $location, $locations);
    }
    $stmt->close();
    return $string;
}


function displayCreateUser($groups, $locations)
{
    $string = "";
    //Add user option
    $string .="<div class='user' id='user'>";
    $string .="<form class='userform' id='userform'>";

    $string .="<input type='text' name='username'>";
    $string .="<input type='number' class='inputlevel' name='level'>";
    $string .="<select name='group'>";
    foreach ($groups as $item) {
        $string .="<option value='$item'>$item</option>";
    }
    $string .="</select>";

    $string .="<select name='location'>";
    foreach ($locations as $item) {
        $string .="<option value='$item'>$item</option>";
    }
    $string .="</select>";
    $string .="<input type='hidden' name='password'>";
    $string .="<input class='ulbutton' type='submit' value='Create' name='create'>";
    $string .="</form>"; //userform
    $string .="</div>";//user

    return $string;
}


/**
 * @param $con
 */
function userManagement($con)
{
    $string = "";
    $string .=  "<div class='usermanagment' id='usermanagment'>";
    $locations = getLocations($con);
    $groups = getGroups($con);
    $string .=  getUsers($con, $groups, $locations);
    $string .=  displayCreateUser($groups, $locations);
    $string .=  "</div>";
    return $string;
}


/**
 * Location Controller Functions
 */

function locationUsage()
{
    if (!isset($_POST['submit'])) {
        echo ("Invalid Usage");
        return false;

    }
    //Standard Var check
    if ((!isset($_POST['name'])) || (strlen($_POST['name']) <= 0)) {
        echo ("Invalid Usage");
        return false;
    }
    if ((!isset($_POST['address'])) || (strlen($_POST['address']) <= 0)) {
        echo ("Invalid Usage");
        return false;
    }

    return true;
}


/**
 * @param $con
 * @return array
 */
function updateLocation($con)
{
    if ((!isset($_POST['idlocation'])) || (strlen($_POST['idlocation']) <= 0)) {
        echo ("Invalid Usage");
        return false;
    }
    $idlocation = htmlspecialchars($_POST['idlocation']);
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);

    //Prepare
    if (!$stmt = $con->prepare("UPDATE Location SET Name=?,Address=? WHERE idLocation=?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("ssi", $name, $address, $idlocation)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    $stmt->close();
    return true;
}

/**
 * @param $con
 * @return mixed
 */
function removeLocation($con)
{
    if ((!isset($_POST['idlocation'])) || (strlen($_POST['idlocation']) <= 0)) {
        echo ("Invalid Usage");
        return false;
    }
    $idlocation = htmlspecialchars($_POST['idlocation']);
    //Prepare
    if (!$stmt = $con->prepare("DELETE FROM Location WHERE idLocation=?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("i", $idlocation)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    $stmt->close();
    return true;
}

/**
 * @param $con
 */
function createLocation($con)
{
    if (isset($_POST['idlocation'])) {
        echo ("Invalid Usage");
        return false;
    }
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);

    //Prepare
    if (!$stmt = $con->prepare("INSERT INTO Location (Name, Address) VALUES (?,?)")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("ss", $name, $address)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}

/**
 * Location Management Functions
 */

function locationLabels()
{
    $string = "";
    $string .= "<div class='locationmanagmentlabels' id='locationmanagmentlabels'>";
    $string .= "<div class='label' id='labe'>Name</div>";
    $string .= "<div class='label' id='labe'>Address</div>";
    $string .= "</div>";//locationmanagmentlabels
    return $string;
}

/**
 * @param $idLocation
 * @param $Name
 * @param $Address
 */
function displayLocation($idLocation, $Name, $Address)
{
    $string = "";
    $string .=  "<div class='location' id='location'>";
    $string .=  "<form class='locationform' id='locationform'>";
    $string .=  "<input type='hidden' name='idlocation' value='$idLocation'>";
    $string .=  "<input type='text' name='name' value='$Name'>";
    $string .=  "<input type='address' name='address' value='$Address'>";
    $string .=  "<input class='ulbutton' type='submit' value='Remove' name='remove'>";
    $string .=  "<input class='ulbutton' type='submit' value='Update' name='update'>";
    $string .=  "</form>"; //userform
    $string .=  "</div>";
    return $string;
}

function displayCreateLocation()
{
    $string = "";
    $string .=  "<div class='location' id ='location'>";
    $string .=  "<form class='locationform' id='locationform'>";
    $string .=  "<input type='text' name='name'>";
    $string .=  "<input type='address' name='address'>";
    $string .=  "<input class='ulbutton' type='submit' value='Create' name='create'>";
    $string .=  "</form>"; //userform
    $string .=  "</div>";
    return $string;
}

/**
 * @param $con
 * @param $idLocation
 * @param $Name
 * @param $Address
 */
function locationManagement($con)
{
    $idLocation = "";
    $Name = "";
    $Address = "";
    $string = "";
//Prepare
    if (!$stmt = $con->prepare("SELECT idLocation,Name,Address FROM Location ORDER BY Name ASC")) {
        $string .= "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//Execute
    if (!$stmt->execute()) {
        $string .= "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//Bind
    if (!$stmt->bind_result($idLocation, $Name, $Address)) {
        $string .= "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $string .= "<div class='locationmanagment' id='locationmanagment'>";
    $string .= locationLabels();
    while ($stmt->fetch()) {
        $string .= displayLocation($idLocation, $Name, $Address);
    }
    $stmt->close();
    $string .= displayCreateLocation();

    $string .= "</div>";
    return $string;
}

/**
 *  Group Controller Functions
 */


function groupUsage()
{
    if (!isset($_POST['submit'])) {
        return false;

    }
//Standard Var check
    if ((!isset($_POST['role'])) || (strlen($_POST['role']) <= 0)) {
        return false;
    }
    return true;
}


/**
 * @param $con
 * @return array
 */
function updateGroup($con)
{
    if ((!isset($_POST['idgroup'])) || (strlen($_POST['idgroup']) <= 0)) {
        echo ("Invalid Usage");
        return false;
    }
    $idgroup = htmlspecialchars($_POST['idgroup']);
    $role = htmlspecialchars($_POST['role']);
    $mgmt = 0;
    $read = 0;
    $write = 0;

    if (isset($_POST['managment'])) {
        $mgmt = 1;
    }
    if (isset($_POST['read'])) {
        $read = 1;
    }
    if (isset($_POST['write'])) {
        $write = 1;
    }

    //Prepare
    if (!$stmt = $con->prepare("UPDATE `Group` 
				SET `Role`=?,`Managment`=?,`Read`=?,`Write`=? 
				WHERE idGroup=?")
    ) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("siiii", $role, $mgmt, $read, $write, $idgroup)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}

/**
 * @param $con
 * @return bool
 */
function removeGroup($con)
{
    if ((!isset($_POST['idgroup'])) || (strlen($_POST['idgroup']) <= 0)) {
        echo("Invalid Usage");
        return false;
    }
    $idgroup = htmlspecialchars($_POST['idgroup']);

    //Prepare
    if (!$stmt = $con->prepare("DELETE FROM `Group` 
                                WHERE idGroup=?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("i", $idgroup)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}

/**
 * @param $con
 */
function createGroup($con)
{
    if (isset($_POST['idgroup'])) {
        echo("Invalid Usage");
        return false;
    }
    $role = htmlspecialchars($_POST['role']);
    $mgmt = 0;
    $read = 0;
    $write = 0;

    if (isset($_POST['managment'])) {
        $mgmt = 1;
    }
    if (isset($_POST['read'])) {
        $read = 1;
    }
    if (isset($_POST['write'])) {
        $write = 1;
    }

    //Prepare
    if (!$stmt = $con->prepare("INSERT INTO   `Group` 
                                              (`Role`,
                                              `Managment`,
                                              `Read`,
                                              `Write`)
				                VALUES(?,?,?,?)")
    ) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    if (!$stmt->bind_param("siii", $role, $mgmt, $read, $write)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    $stmt->close();
    return true;
}

/**
 * Group Management Functions
 */

/**
 * @param $idGroup
 * @param $role
 * @param $managment
 * @param $read
 * @param $write
 */
function displayGroup($idGroup, $role, $managment, $read, $write)
{
    $string = "";
    $string .= "<div class='group' id='group'>";
    $string .= "<form class='groupform' id='groupform'>";
    $string .= "<input type='hidden' name='idgroup' value='$idGroup'>";
    $string .= "<input class='grouptext' type='text' name='role' value='$role'>";
    $string .= "<input class='groupcheck' type='checkbox' name='managment' value='$managment'";
    if ($managment == 1) {
        $string .= "checked";
    }
    $string .= ">";
    $string .= "<input  class='groupcheck' type='checkbox' name='read' value='$read'";
    if ($read == 1) {
        $string .= "checked";
    }
    $string .= ">";
    $string .= "<input  class='groupcheck' type='checkbox' name='write' value='$write'";
    if ($write == 1) {
        $string .= "checked";
    }
    $string .= ">";
    $string .= "<input class='ulbutton' type='submit' value='Remove' name='remove'>";
    $string .= "<input class='ulbutton' type='submit' value='Update' name='update'>";
    $string .= "</form>"; //groupform
    $string .= "</div>";
    return $string;
}

function groupLabels()
{
    $string = "";
    $string .= "<div class='groupmanagmentlabels' id='groupmanagmentlabels'>";
    $string .= "<div class='labelrole' id='labe'>Role</div>";
    $string .= "<div class='label' id='labe'>Management</div>";
    $string .= "<div class='label' id='labe'>Read</div>";
    $string .= "<div class='label' id='labe'>Write</div>";
    $string .= "</div>";//locationmanagmentlabels
    return $string;
}

function displayCreateGroup()
{
    $string = "";
    $string .= "<div class='group' id ='group'>";
    $string .= "<form class='groupform' id='groupform'>";
    $string .= "<input type='text' class='grouptext' name='role'>";
    $string .= "<input  class='groupcheck' type='checkbox' name='managment'>";
    $string .= "<input  class='groupcheck' type='checkbox' name='read'>";
    $string .= "<input  class='groupcheck' type='checkbox' name='write'>";
    $string .= "<input class='ulbutton' type='submit' value='Create' name='create'>";
    $string .= "</form>"; //groupform
    $string .= "</div>";
    return $string;
}

/**
 * @param $con
 */
function groupManagement($con)
{
    $string = "";
    $idGroup = "";
    $managment = "";
    $role = "";
    $write = "";
    $read = "";
//Prepare
    if (!$stmt = $con->prepare("SELECT idGroup, Role, Managment, `Write`, `Read` FROM `Group`")) {
        $string .=  "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//Execute
    if (!$stmt->execute()) {
        $string .=  "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//Bind
    if (!$stmt->bind_result($idGroup, $role, $managment, $write, $read)) {
        $string .=  "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }


    $string .=  "<div class='groupmanagment' id='groupmanagment'>";

    $string .=  groupLabels();
    while ($stmt->fetch()) {
        $string .=  displayGroup($idGroup, $role, $managment, $read, $write);

    }
    $stmt->close();

    $string .=  displayCreateGroup();

    $string .=  "</div>";
    return $string;
}

