<?php

function getConnection() {
    $host = "localhost";
    $database_name = "hall_booking_system";
    $username = "root";
    $password = "root";

    try {
        $conn = new PDO(
            "mysql:host=$host;dbname=$database_name;charset=utf8",
            $username,
            $password
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $exception) {
        echo "Connection error: " . $exception->getMessage();
        exit();
    }
}
?>