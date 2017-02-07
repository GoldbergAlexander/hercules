<?php
require_once "/var/www/html/database/dbconnect.php";
if(isset($_SESSION)){
	session_unset();
	session_destroy();
	echo "<div class='logout' id='logout'>";
	echo "Succesfuly Logged Out";
	echo "</div>";
}
