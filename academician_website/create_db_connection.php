<?php

// Veritabanı bağlantı bilgileri
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "form_db";
$port = 3307;

// Veritabanı bağlantısı
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Bağlantı kontrolü
if ($conn->connect_error) {
    exit("Connection failed: " . $conn->connect_error);
}
?>
