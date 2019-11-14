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
function password_array($connection, $db) {
    $query = "SELECT `UserPassword` FROM `sharex`";
    $result = $connection->query($query);
    $password_array = array();

    while($row = mysqli_fetch_array($result)) {
        //$password_array[] = "\"".$row['UserPassword']."\"";
        $password_array[] = $row['UserPassword'];
    }
    // not really needed just for the future TODO: REMOVE THIS
    $password_int = implode(",", $password_array);
    return $password_array;
}

if(isset($_POST['token'])) {
    if(in_array($_POST['token'], password_array($connection, dbsettings::$db))) {
        if($randomstring) {
            $file = generateRandomString($length);
            $target = $_FILES["sharex"]["name"];
            $type = pathinfo($target, PATHINFO_EXTENSION);

            if(move_uploaded_file($_FILES["sharex"]["tmp_name"], $dir.file.'.'.$type)) {
                $output = $url . "/" . $dir . $file . "." . $type; //TODO : RETURN TO CLIENT
            } else {
                echo "Could not upload file to $dir";
            }
        }
        else {
            // TODO: know how to get filename from sharex
            /*
            $target = explode(".", $_FILES["sharex"]["name"];
            $type = pathinfo($target, PATHINFO_EXTENSION);
            if(move_uploaded_file($_FILES["sharex"]["tmp_name"], $dir.file.".".$type)) {
                $output = $url . "/" . $dir . $target . "." . $type;
            } else {
                echo "Could not upload file to $dir";
            }
            */
        }
    } else {
        echo "Wrong token";
    }
} else {
    echo "No data received from client.";
}

$connection->close();
?>


