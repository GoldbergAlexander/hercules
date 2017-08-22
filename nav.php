<?php
require_once '/var/www/html/database/dbconnect.php';

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

echo "<div class='navbutton'>";
    echo "<div class='mainbutton' id='home'>";
        echo "HOME";
    echo "</div>";
echo "</div>";

if(!isset($_SESSION['username'])){
echo "<div class='navbutton' >";
    echo "<div class='mainbutton' id='login'>";
        echo "LOGIN";
    echo "</div>";
echo "</div>";

}
if(isset($_SESSION['username'])){
echo "<div class='navbutton'>";
    echo "<div class='mainbutton' id='entry'>";
        echo "ENTRY";
    echo "</div>";
/*
    echo "<div class='dropdown'>";
        echo "<div id='daily'>Daily</div>";
        echo "<div id='department'>Departmental</div>";
    echo "</div>";
*/

echo "</div>";

echo "<div class='navbutton'>";
    echo "<div class='mainbutton' id='display'>";
        echo "DISPLAY";
    echo "</div>";
echo "</div>";

echo "<div class='navbutton'>";
    echo "<div class='mainbutton' id='account'>";
        echo "ACCOUNT";
    echo "</div>";
echo "</div>";

echo "<div class='navbutton'>";
    echo "<div class='mainbutton' id='managment'>";
        echo "MANAGEMENT";
    echo "</div>";
 /*   echo "<div class='dropdown'>";
        echo "<div id='users'>Users</div>";
        echo "<div id='locations'>Locations</div>";
        echo "<div id='departments'>Departments</div>";
        echo "<div id='groups'>Groups</div>";
    echo "</div>";
*/

echo "</div>";

echo "<div class='navbutton'>";
    echo "<div class='mainbutton' id='logout'>";
        echo "LOGOUT";
    echo "</div>";
echo "</div>";
}




