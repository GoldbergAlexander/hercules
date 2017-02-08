<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php'; //login check
require_once '/var/www/html/entry/entryLibrary.php';

secure();
echo entryClear();

