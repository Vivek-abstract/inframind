<?php

include 'includes/connect.inc.php';
$stmt = $conn->prepare('SELECT name, email FROM users');
$stmt->execute();

$users = $stmt->fetchAll();

require 'views/welcome.php';

?>
