<?php
require_once '/var/www/html/database/dbconnect.php';
function accountInfo()
{
    $string = "";
    $string .=  "<div>";
    $string .=  "<span>Username:</span>";
    $string .=  "</br>";
    $string .=  "<span>Access Level:</span>";
    $string .=  "</br>";
    $string .=  "<span>Role:</span>";
    $string .=  "</br>";
    $string .=  "<span>Assigned Location:</span>";
    $string .=  "</br>";
    $string .=  "<span>IP Address:</span>";
    $string .=  "</br>";
    $string .=  "<span>Server Time:</span>";

    $string .=  "</div>";
    $string .=  "<div>";
    $string .=  "<span>" . $_SESSION['username'] . "</span>";
    $string .=  "</br>";
    $string .=  "<span>" . $_SESSION['level'] . "</span>";
    $string .=  "</br>";
    $string .=  "<span>" . $_SESSION['role'] . "</span>";
    $string .=  "</br>";
    $string .=  "<span>" . $_SESSION['location'] . "</span>";
    $string .=  "</br>";
    $string .=  "<span>" . $_SERVER['REMOTE_ADDR'] . "</span>";
    $string .=  "</br>";
    $string .=  "<span>" . date('Y-m-d H:i:s') . "  " . (date('T')) . "</span>";
    $string .=  "</div>";

    return $string;
}


function accountPassword()
{
    $string = "";
    $string .=  "<form class='accountpasswordchangeform' id='accountpasswordchangeform'>";
    $string .=  "<div>";
    $string .=  "<span>Old Password:</span></br>";
    $string .=  "<span>New Password:</span></br>";
    $string .=  "<span>Confirm New Password:</span>";
    $string .=  "</div>";

    $string .=  "<div>";
    $string .=  "<input type='password' name='oldpass'></br>";
    $string .=  "<input type='password' name='pass'></br>";
    $string .=  "<input type='password' name='passconfirm'></br>";
    $string .=  "<input class='accountpasswordchangeupdate' type='submit' name='submit' value='Update'>";
    $string .=  "</div>";
    $string .=  "</form>";

    $string .=  "<div class='accountpasswordstrength'>";
    $string .=  "</div>";
    return $string;
}

function verifyPassword($con)
{
    $return = false;
    $hash = "";
    if (!isset($_POST['oldpass'])) {
        die("Old Password Required");
    }
    $oldpass = $_POST['oldpass'];
    if (strlen($oldpass) <= 0) {
        die("Old Passoword Required");
    }

    if (!$stmt = $con->prepare("SELECT Hash FROM Users WHERE Username = ?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
    }
//Bind
    if (!$stmt->bind_param("s", $_SESSION['username'])) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//Bind
    if (!$stmt->bind_result($hash)) {
        echo "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//Should be a single line
    $return = false;
    while ($stmt->fetch()) {
        if (password_verify($oldpass, $hash)) {
            $return = true;
        }
    }
    $stmt->close();
    return $return;
}

function updatePassword($con)
{
    $return = false;
    if (!isset($_POST['pass'],$_POST['passconfirm'])) {
        echo "Password entries must match";
        return $return;
    }

    $pass = $_POST['pass'];
    $passConfirm = $_POST['passconfirm'];

    if ($pass != $passConfirm) {
        echo "Password entries must match";
        return $return;
    }
    if (strlen($pass) <= 0) {
        echo "Password can not be blank";
        return $return;
    }
    if (strlen($pass) <= MIN_PASS_LENGTH) {
        echo "Please enter a password nine (9) chars or longer";
        return $return;
    }
    $username = $_SESSION['username'];
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    //Prepare
    if (!$stmt = $con->prepare("UPDATE Users SET Hash = ? WHERE Username = ?")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return $return;
    }
    //Bind
    if (!$stmt->bind_param("ss", $hash, $username)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return $return;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return $return;
    }
    $stmt->close();

    $return = true;
    return $return;
}

function changePassword($con)
{
    $string = "";
    if (!verifyPassword($con)) {
        die("Username or Password Invalid");
    } else {
        if (updatePassword($con)) {
            $string .= "Password Updated";
        } else {
            $string .= "Error Updating Password";
        }
    }
    return $string;
}

