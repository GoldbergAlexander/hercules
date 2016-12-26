<?php

require_once 'dbconnect.php';
require 'security.php';

echo "<div>";
echo "<span>Username:</span>";
echo "</br>";
echo "<span>Access Level:</span>";
echo "</br>";
echo "<span>Role:</span>";
echo "</br>";
echo "<span>Assigned Location:</span>";
echo "</br>";
echo "<span>IP Address:</span>";
echo "</br>";
echo "<span>Server Time:</span>";

echo "</div>";
echo "<div>";
echo "<span>" . $_SESSION['username'] . "</span>";
echo "</br>";
echo "<span>" . $_SESSION['level'] . "</span>";
echo "</br>";
echo "<span>" . $_SESSION['role'] . "</span>";
echo "</br>";
echo "<span>" . $_SESSION['location'] . "</span>";
echo "</br>";
echo "<span>" . $_SERVER['REMOTE_ADDR'] . "</span>";
echo "</br>";
echo "<span>" . date('Y-m-d H:i:s') . "  " .  (date('T')) . "</span>";


echo "</div>";
