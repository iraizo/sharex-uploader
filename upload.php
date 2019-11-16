<?php

error_reporting(E_ALL);

include "config.php";

$connection = new mysqli($config['host'], $config['user'], $config['password'], $config['db']); // TODO: use other framework because of sql injection (muh sql injection)

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
        if($config['randomstring']) {
            $filename = generateRandomString($config['length']); // TODO MOVE THIS SO I DONT NEED TO REPEAT CODE
            $target = $_FILES["x"]["name"];
            $extension = pathinfo($target, PATHINFO_EXTENSION);

            if (move_uploaded_file($_FILES["x"]["tmp_name"], $config['dir'].$filename.'.'.$extension)) {
                echo $config['url'] . $config['dir'] . $filename . '.' . $extension;
            } else {
                echo "Possible permission error contact the server administrator.";
            }


        } else {
            // TODO: get file name from sharex and dont use generateRandomString
        }
    } else {
        echo "<h1>Wrong Token</h1><br> contact the server administrator.";
    }
} else {
    echo "No post data received from client.";
}

$connection->close();

unset($config);
