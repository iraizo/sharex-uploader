<?php

error_reporting(E_ALL);

$config = include("config.php");

$url = $config['url'];
$randomstring = true; // if the url will get a random string or not
$length = 5; // random name length
$dir = "p/"; // directory pictures are getting uploaded to


class dbsettings { // you need to change all of this
    public static $query = "SELECT * FROM `sharex`";
    public static $server = "localhost";
    public static $user = "sharexadmin";
    public static $pass = "tessa";
    public static $db = "sharex";
}

$connection = new mysqli(dbsettings::$server, dbsettings::$user, dbsettings::$pass, dbsettings::$db);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
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

function getuserinfo($connection, $db, $uid, $row) {
    $query = "SELECT * FROM $db WHERE UID=$uid";
    $result = $connection->query($query);
    $rowresult = mysqli_fetch_array($result);
    $information = $rowresult[$row];
    return $information;
}

function TokenExists(string $token, $connection)
{

    $query = 'SELECT COUNT(UserPassword) AS amount FROM sharex WHERE UserPassword = "' . $connection->mysqli_escape_string($token). '"';
    /*
    $result = $connection->query($query);
    $row = mysqli_fetch_array($result);
    return $row["amount"] > 0;*/
    echo $query;
    return true;
}   


if(isset($_POST['token'])) {
    //if(TokenExists($_POST['token'], dbsettings::$db, $connection)) {
    if(TokenExists($_POST['token'], $connection)) {
        echo "Token exists in database";
    } else {
        echo "token dosent exist in database";
    }
}

$connection->close();
