<?php
// Veritabanı bağlantı bilgileri
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'form_db';
$port = 3307;

// Veritabanı bağlantısı
$connection_academics = new mysqli($servername, $username, $password, $dbname,$port);

// Bağlantıyı kontrol et
if ($connection_academics->connect_error) {
    die("Connection failed: " . $connection_academics->connect_error);
}

?>