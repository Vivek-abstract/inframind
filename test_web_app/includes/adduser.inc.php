<?php

include 'connect.inc.php';

$name = $_POST['name'];
$email = $_POST['email'];

$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
$stmt->bindParam(':name', $name, PDO::PARAM_STR, 50);
$stmt->bindParam(':email', $email, PDO::PARAM_STR, 255);
$stmt->execute();

header("Location: /welcome.php");
exit();