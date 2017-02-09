<?php
function loginUsage()
{
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo ("Expected Username and Password");
        return false;
    }
    return true;
}

/**
 * @param $con
 * @param $username
 * @param $pastDate
 * @param $currentDate
 * @param $retCount
 * @return mixed
 */
function failedLogins($con, $username, $pastDate, $currentDate, $retCount)
{
//Prepare
    if (!$stmt = $con->prepare(" SELECT COUNT(*) 
                            FROM (
                                  SELECT * 
                                  FROM Authentication 
                                  WHERE Auth_idUsers = (
                                                        SELECT idUsers 
                                                        FROM Users 
                                                        WHERE Username = ?) 
                                  AND Result = 0 
                                  AND `Date` 
                                  BETWEEN ? AND ?) AS x")
    ) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
//Bind
    if (!$stmt->bind_param("sss", $username, $pastDate, $currentDate)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
//Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
//Bind
    if (!$stmt->bind_result($retCount)) {
        echo "Bind Result Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }

    while ($stmt->fetch()) {

    }

    $stmt->close();
    return $retCount;
}


//Should be a single line
/**
 * @param $username
 * @param $level
 * @param $location
 * @param $hash
 * @param $role
 * @param $m
 * @param $w
 * @param $r
 * @return int
 */
function login($username, $level, $location, $hash, $role, $m, $w, $r)
{
    echo "valid";
    $_SESSION['username'] = $username;
    $_SESSION['level'] = $level;
    $_SESSION['location'] = $location;
    $_SESSION['hash'] = $hash;
    $_SESSION['role'] = $role;
    $_SESSION['group'] = array("m" => $m, "w" => $w, "r" => $r);
    $_SESSION['last_login'] = time(); //last time password was confirmed with DB
    $result = 1;
    return $result;
}


/* We add here on success OR failure.*/
/**
 * @param $con
 * @param $myUserId
 * @param $result
 */
function registerAttempt($con, $myUserId, $result)
{
    //Prepare
    if (!$stmt = $con->prepare("INSERT INTO Authentication (Auth_idUsers,Date,IP,Result) VALUES (?,?,?,?)")) {
        echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
        return false;
    }
    //Bind
    $datetime = date("Y-m-d H:i:s");
    $remoteAddr = ip2long($_SERVER['REMOTE_ADDR']);
    if (!$stmt->bind_param("isii", $myUserId, $datetime, $remoteAddr, $result)) {
        echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    //Execute
    if (!$stmt->execute()) {
        echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    }
    $stmt->close();
    return true;
}
