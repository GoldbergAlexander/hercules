<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/management/managementLibrary.php';
secure();

echo "<div class='password'></div>";

echo locationManagement($con);

