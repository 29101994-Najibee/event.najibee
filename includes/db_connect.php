<?php
function getDatabaseConnection() {
    $host = 'localhost';
    $dbname = 'najibee_evenementenregistratie';
    $dbusername = 'root';
    $dbpassword = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Connection Failed: ' . $e->getMessage());
    }
}