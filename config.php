<?php
$host = 'localhost';
$db   = 'dbfilm';
$user = 'root'; // ganti dengan username MySQL Anda
$pass = ''; // ganti dengan password MySQL Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>