<?php

$config = include("config.php");

$url = $config['url'];
$randomstring = true; // if the url will get a random string or not
$length = 5; // random name length
$dir = "p/"; // directory pictures are getting uploaded to


class dbsettings { // you need to change all of this
    public static $query = "SELECT * FROM `sharex`";
    public static $server = "localhost";
    public static $user = "root";
    public static $pass;
    public static $db = "sharex";
}



function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$connection = new mysqli(dbsettings::$server, dbsettings::$user, dbsettings::$pass, dbsettings::$db)
    OR die(mysqli_error());

function getuserinfo($connection, $db, $uid, $row) {
    $query = "SELECT * FROM $db WHERE UID=$uid";
    $result = $connection->query($query);
    $rowresult = mysqli_fetch_array($result);
    $information = $rowresult[$row];
    return $information;
}
function token_array($connection, $db) {
    $query = "SELECT `UserPassword` FROM `sharex`";
    $result = $connection->query($query);
    $token_array = array();

    while($row = mysqli_fetch_array($result)) {
        print($row['UserPassword']);
        $token_array[] = $row['UserPassword'];
    }
    return $token_array;
}

$tokenlist = token_array($connection, dbsettings::$db);
if(isset($_POST['token'])) {
    if (in_array($_POST["token"], $tokenlist)) {
        echo "token is in array";
    }
    else {
        echo "not in array";
        echo " Request: " .$_POST['token'] . " Array: " . $tokenlist;
    }
}
$connection->close();
?>