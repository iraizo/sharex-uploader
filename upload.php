<?php

error_reporting(E_ALL);

$config = include("config.php");

$url = $config['url'];
$db = $config['db'];
$server = $config['server'];
$user = $config['user'];
$pass = $config['pass'];
$dir = $config['directory'];
$length = $config['randomstringlength'];
$randomstring = $config['randomstring'];

try {
    $connection = new PDO("mysql:host=localhost;dbname=example", "user", "pass"); //TODO USE THIS WITH CONFIGS
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    function TokenExists(string $token, $connection)
    {
    $query = $connection->prepare('SELECT COUNT(UserPassword) FROM sharex WHERE UserPassword = "?"');
    $result = $query->execute(array($token));
    $row = $query->fetchAll();
    return $row > 0;
    }


    if (isset($_POST['token'])) {
        if (TokenExists($_POST['token'], $connection)) {
            if ($randomstring) {
                $filename = generateRandomString($length); // TODO MOVE THIS SO I DONT NEED TO REPEAT CODE
                $target = $_FILES["x"]["name"];
                $extension = pathinfo($target, PATHINFO_EXTENSION);

                if (move_uploaded_file($_FILES["x"]["tmp_name"], $dir . $filename . '.' . $extension)) {
                    echo $url . $dir . $filename . '.' . $extension;
                } else {
                    echo "Possible permission error contact the server administrator.";
                }


            } else {
                
            }
        } else {
            echo "Wrong Token contact the server administrator.";
        }
    } else {
        echo "No post data received from client.";
    }
} catch(PDOException $e)
{
    echo "PDO Error: " . $e->getMessage();
}
$connection = null;
