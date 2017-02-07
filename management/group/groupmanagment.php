<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
secure();

/*outline*/

//get groups

//Prepare
if(!$stmt = $con->prepare("SELECT idGroup, Role, Managment, `Write`, `Read` FROM `Group`")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($idGroup, $role, $managment, $write, $read)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}


echo "<div class='groupmanagment' id='groupmanagment'>";
	echo "<div class='groupmanagmentlabels' id='groupmanagmentlabels'>";
		echo "<div class='labelrole' id='labe'>Role</div>";
		echo "<div class='label' id='labe'>Management</div>";
		echo "<div class='label' id='labe'>Read</div>";
		echo "<div class='label' id='labe'>Write</div>";	
	echo "</div>";//locationmanagmentlabels

while($stmt->fetch()){
	echo "<div class='group' id='group'>";
		echo "<form class='groupform' id='groupform'>";
			echo "<input type='hidden' name='idgroup' value='$idGroup'>";
			echo "<input class='grouptext' type='text' name='role' value='$role'>";
			echo "<input class='groupcheck' type='checkbox' name='managment' value='$managment'";
			if($managment == 1){echo "checked";}
			echo ">";
			echo "<input  class='groupcheck' type='checkbox' name='read' value='$read'";	
			if($read == 1){echo "checked";}
			echo ">";
			echo "<input  class='groupcheck' type='checkbox' name='write' value='$write'"; 
			if($write == 1){echo "checked";}
			echo ">";
			echo "<input class='ulbutton' type='submit' value='Remove' name='remove'>";
			echo "<input class='ulbutton' type='submit' value='Update' name='update'>";
		echo "</form>"; //groupform
	echo "</div>";

}
$stmt->close();
echo "<div class='group' id ='group'>";
echo "<form class='groupform' id='groupform'>";
echo "<input type='text' class='grouptext' name='role'>";
echo "<input  class='groupcheck' type='checkbox' name='managment'>";
echo "<input  class='groupcheck' type='checkbox' name='read'>";
echo "<input  class='groupcheck' type='checkbox' name='write'>";
echo "<input class='ulbutton' type='submit' value='Create' name='create'>";
echo "</form>"; //groupform
echo "</div>";

echo "</div>";//groupmanagmebt
