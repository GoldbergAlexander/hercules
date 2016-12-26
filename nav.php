<?php
require_once 'dbconnect.php';

//Display Pages

/*Page list
 * Home
 * Entry
 * Display
 * Login -- if not logged in
 * Account -- if logged in
 * Logout -- if logged in
 * Registet -- if not logged in
 */

//using divs as a set of nav buttons

echo "<div class='navbutton' id='home'>";
echo "HOME";
echo "</div>";

if(!isset($_SESSION['username'])){
echo "<div class='navbutton' id='login'>";
echo "LOGIN";
echo "</div>";

}
if(isset($_SESSION['username'])){
echo "<div class='navbutton' id='entry'>";
echo "ENTRY";
echo "</div>";

echo "<div class='navbutton' id='display'>";
echo "DISPLAY";
echo "</div>";

echo "<div class='navbutton' id='account'>";
echo "ACCOUNT";
echo "</div>";

echo "<div class='navbutton' id='managment'>";
echo "MANAGMENT";
echo "</div>";

echo "<div class='navbutton' id='logout'>";
echo "LOGOUT";
echo "</div>";
}




