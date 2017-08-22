<?php
require_once '/var/www/html/database/dbconnect.php';

echo "<!DOCTYPE html>";

echo "<html>";
echo "<link rel='stylesheet' href='css.css'>";
echo "<link rel='stylesheet' href='/token-field/stylesheets/token-field.css' type='text/css' media='screen'>";
echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>";
echo "<script type='text/javascript' src='/fc/js/fusioncharts.js'></script>";
echo "<script type='text/javascript' src='/fc/js/themes/fusioncharts.theme.ocean.js'></script>";
echo "<script src='/jquery.js'></script>";
echo "<head>";
echo "</head>";

echo "<body>";

echo "<div class='page' id='page'>";

echo "<div class='banner' id='banner'>";
echo "</div>"; //banner

echo "<div class='nav' id='nav'>";
include 'nav.php';
echo "</div>"; //nav

echo "<div class='status' id='status'>";
echo "</div>";// status

echo "<div class='main' id='main'>";
echo "</div>"; //main

echo "<div class='footer' id='footer'>";

echo "</div>"; //footer

echo "</div>"; //page

echo "</body>"; 

echo "</html>";

?>
