<?php

error_reporting(E_ALL);

$config = include("config.php");

$url = $config['url'];
$randomstring = true; // if the url will get a random string or not
$length = 5; // random name length
$dir = "p/"; // directory pictures are getting uploaded to


class dbsettings { // you need to change all of this
    public $db = "sharex";
    public $query;
    public static $server = "localhost";
    public static $user = "sharexadmin";
    public static $pass = "tessa";

    function __constructor()
    {
        $this->query = "SELECT * FROM `" . $this->db ."`"; // TODO: find a actual purpose for this rofl.
    }
}



$connection = new mysqli(dbsettings::$server, dbsettings::$user, dbsettings::$pass, dbsettings::$db); // TODO: use other framework because of sql injection

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

    $query = 'SELECT COUNT(UserPassword) AS amount FROM sharex WHERE UserPassword = "' . $token. '"';
    $result = $connection->query($query);
    $row = mysqli_fetch_array($result);
    return $row["amount"] > 0;
}


if(isset($_POST['token'])) {
    if(TokenExists($_POST['token'], $connection)) {
        if($randomstring) {
            $filename = generateRandomString($length); // TODO MOVE THIS SO I DONT NEED TO REPEAT CODE
            $target = $_FILES["x"]["name"];
            $extension = pathinfo($target, PATHINFO_EXTENSION);

            if (move_uploaded_file($_FILES["x"]["tmp_name"], $dir.$filename.'.'.$extension)) {
                echo $url . $dir . $filename . '.' . $extension;
            } else {
                echo "Possible permission error contact the server administrator.";
            }


        } else {
            // TODO: get file name from sharex and dont use generateRandomString
        }
    } else {
        echo "Wrong Token contact the server administrator.";
    }
} else {
    echo "No post data received from client.";
}

$connection->close();