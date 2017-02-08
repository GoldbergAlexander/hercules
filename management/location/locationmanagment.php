<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/management/managementLibrary.php';
secure();

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
echo locationLabels();
while($stmt->fetch()){
   echo displayLocation($idLocation, $Name, $Address);
}
$stmt->close();
echo displayCreateLocation();

echo "</div>";//locationmanagmebt
