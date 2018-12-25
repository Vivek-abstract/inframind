<?php

$servername = "10.0.1.133";
$username = "groot";
$password = "groot";
$dbname = 'synergy';

try {
    $conn = new PDO("mysql:host=$servername;dbname=synergy", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
