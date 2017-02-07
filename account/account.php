<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
secure();

//Information
echo "<div class='accountinfo' id='accountinfo'>";
include '/var/www/html/account/accountinfo.php';
echo "</div>";

//Password Change
echo "<div class='accountpasswordchange' id='accountpasswordchange'>";
include '/var/www/html/account/accountpasswordchange.php';
echo "</div>";




