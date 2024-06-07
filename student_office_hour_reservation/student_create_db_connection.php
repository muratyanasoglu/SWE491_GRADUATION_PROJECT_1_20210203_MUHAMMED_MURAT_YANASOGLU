<?php
// Student Database connection
$student_db_host = 'localhost';
$student_db_username = 'root';
$student_db_password = '';
$student_db_name = 'students_database';
$student_port = 3307;

// Create student database connection
$student_connection = new mysqli($student_db_host, $student_db_username, $student_db_password, $student_db_name, $student_port);

// Check connection errors
if ($student_connection->connect_error) {
    die("Student database connection error: " . $student_connection->connect_error);
}

// Academic Database connection
$academic_db_host = 'localhost';
$academic_db_username = 'root';
$academic_db_password = '';
$academic_db_name = 'form_db';
$academic_port = 3307;

// Create academic database connection
$academic_connection = new mysqli($academic_db_host, $academic_db_username, $academic_db_password, $academic_db_name, $academic_port);

// Check connection errors
if ($academic_connection->connect_error) {
    die("Academic database connection error: " . $academic_connection->connect_error);
}
?>
