<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/account/accountLibrary.php';
secure();

//Information
echo "<div class='accountinfo' id='accountinfo'>";
echo accountInfo();
echo "</div>";

//Password Change
echo "<div class='accountpasswordchange' id='accountpasswordchange'>";
echo accountPassword();
echo "</div>";




