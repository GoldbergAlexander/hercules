<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php'; //login check
require_once '/var/www/html/entry/entryLibrary.php'; //login check

secure();
echo entryReview();


