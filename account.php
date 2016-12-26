<?php
require_once 'dbconnect.php';
require 'security.php';

//Information
echo "<div class='accountinfo' id='accountinfo'>";
include 'accountinfo.php';
echo "</div>";

//Password Change
echo "<div class='accountpasswordchange' id='accountpasswordchange'>";
include 'accountpasswordchange.php';
echo "</div>";




