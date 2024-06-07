<?php
// Database connection variables
$db_host = 'localhost';
$db_username = 'root'; // Database username
$db_password = ''; // Database password (usually empty in XAMPP)
$db_name = 'students_database'; // Database name
$port = 3307; // Veritabanı sunucusu ve portu

// Create database connection
$connection = new mysqli($db_host, $db_username, $db_password, $db_name, $port);

// Check connection errors
if ($connection->connect_error) {
    die("Database connection error: " . $connection->connect_error);
}


?>
