<?php
// Öğrenci veritabanı bağlantı bilgileri
$student_db_host = 'localhost';
$student_db_username = 'root';
$student_db_password = '';
$student_db_name = 'students_database';
$student_db_port = 3307;

// Öğrenci veritabanı bağlantısı
$connection_students = new mysqli($student_db_host, $student_db_username, $student_db_password, $student_db_name, $student_db_port);

// Bağlantıyı kontrol et
if ($connection_students->connect_error) {
    die("Connection failed: " . $connection_students->connect_error);
}
?>
