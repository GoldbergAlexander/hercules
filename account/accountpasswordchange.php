<?php
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
secure();

echo "<form class='accountpasswordchangeform' id='accountpasswordchangeform'>";
echo "<div>";
echo "<span>Old Password:</span></br>";
echo "<span>New Password:</span></br>";
echo "<span>Confirm New Password:</span>";
echo "</div>";

echo "<div>";
echo "<input type='password' name='oldpass'></br>";
echo "<input type='password' name='pass'></br>";
echo "<input type='password' name='passconfirm'></br>";
echo "<input class='accountpasswordchangeupdate' type='submit' name='submit' value='Update'>";
echo "</div>";
echo "</form>";

echo "<div class='accountpasswordstrength'>";
echo "</div>";
