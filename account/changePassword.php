<?php
const MIN_PASS_LENGTH = 8;
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
require_once '/var/www/html/account/accountLibrary.php';
secure();
echo changePassword($con);
