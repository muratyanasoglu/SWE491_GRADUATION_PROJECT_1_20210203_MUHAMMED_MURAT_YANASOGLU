<?php
session_start();
require_once('config_db.php');
require_once('config_students.php');

if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Accepted reservation'ı sil
    $query = "DELETE FROM form_db.accepted_reservations WHERE id = '$reservation_id'";
    if ($connection_academics->query($query) === TRUE) {
        // Öğrenci veritabanındaki ilgili rezervasyonu da sil
        $query_student = "DELETE FROM students_database.accepted_reservations WHERE id = '$reservation_id'";
        if ($connection_students->query($query_student) === TRUE) {
            header("Location: accepted_reservations.php");
        } else {
            echo "Error: " . $connection_students->error;
        }
    } else {
        echo "Error: " . $connection_academics->error;
    }
}
?>
