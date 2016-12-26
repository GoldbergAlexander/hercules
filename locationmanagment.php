<?php
require_once 'dbconnect.php';
require 'security.php';

/*outline*/

//get locations

//Prepare
if(!$stmt = $con->prepare("SELECT idLocation,Name,Address FROM Location ORDER BY Name ASC")){
	echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
}
//Execute
if(!$stmt->execute()){
	echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
}
//Bind
if(!$stmt->bind_result($idLocation,$Name,$Address)){
	echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
}


echo "<div class='locationmanagment' id='locationmanagment'>";
	echo "<div class='locationmanagmentlabels' id='locationmanagmentlabels'>";
		echo "<div class='label' id='labe'>Name</div>";
		echo "<div class='label' id='labe'>Address</div>";
	echo "</div>";//locationmanagmentlabels

while($stmt->fetch()){
	echo "<div class='location' id='location'>";
		echo "<form class='locationform' id='locationform'>";
			echo "<input type='hidden' name='idlocation' value='$idLocation'>";
			echo "<input type='text' name='name' value='$Name'>";
			echo "<input type='address' name='address' value='$Address'>";
			echo "<input class='ulbutton' type='submit' value='Remove' name='remove'>";
			echo "<input class='ulbutton' type='submit' value='Update' name='update'>";
		echo "</form>"; //userform
	echo "</div>";

}
$stmt->close();
echo "<div class='location' id ='location'>";
echo "<form class='locationform' id='locationform'>";
echo "<input type='text' name='name'>";
echo "<input type='address' name='address'>";
echo "<input class='ulbutton' type='submit' value='Create' name='create'>";
echo "</form>"; //userform
echo "</div>";

echo "</div>";//locationmanagmebt
