<?php
require_once 'dbconnect.php';
require 'security.php'; //login check

if(!isset($_SESSION['submitdata'])){
	die("Error no data stored");
}

$array = $_SESSION['submitdata'];
//Display
echo "<div class='review'>";

echo "<div class='reviewoutput'>";
echo "<div>Location</div>";
echo "<div class='item'>".$array['location']."</div>";
echo "</div>";

/*
echo "<div class='reviewoutput'>";
echo "Department ";
echo $array['department'];
echo "</div>";
 */

echo "<div class='reviewoutput'>";
echo "<div>Date</div>";
echo "<div class='item'>" . $array['date'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Transaction Count</div>";
echo "<div class='item'>" . $array['transcount'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Cash Count</div>";
echo "<div class='item'>" . $array['cashcount'] . "</div>";

echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Check Count</div>";
echo "<div class='item'>" . $array['checkcount'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Payout</div>";
echo "<div class='item'>" . $array['payout'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Card Unit</div>";
echo "<div class='item'>" . $array['cardunit'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Cash Tape</div>";
echo "<div class='item'>" . $array['cashtape'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Check Tape</div>";
echo "<div class='item'>" . $array['checktape'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Card Tape</div>";
echo "<div class='item'>" . $array['cardtape'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Tax Tape</div>";
echo "<div class='item'>" . $array['taxtape'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Sales Void</div>";
echo "<div class='item'>" . $array['salesvoid'] . "</div>";
echo "</div>";

echo "<div class='reviewoutput'>";
echo "<div>Tax Void</div>";
echo "<div class='item'>" . $array['taxvoid'] . "</div>";
echo "</div>";

if($array['memo'] != ""){
echo "<div class='reviewoutput'>";
echo "<div>Memo</div>";
echo "<div class='item'>" . $array['memo'] . "</div>";
echo "</div>";
}

echo "</div>";

echo "<div class='reviewdo'>";

echo "<form id='submitdata'>";
echo "<input type='submit' name='submit' value='Submit'>";
echo "</form>";

echo "<form id='backentry'>";
echo "<input type='submit' name='submit' value='Modify'>";
echo "</form>";

echo "<form id='cancelsubmit'>";
echo  "<input type='submit' name='submit' value='Reset'>";
echo "</form>";

echo "</div>";

include "entrybuttons.php";
