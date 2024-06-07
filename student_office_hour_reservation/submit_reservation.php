<?php
session_start();
require_once('config.php');
require_once('config_db.php'); // Academics database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $academic_id = $_POST['academic_id'];
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $student_number = $_POST['student_number'];
    $reservation_reason = $_POST['reservation_reason'];
    $reservation_details = $_POST['reservation_details'];

    $query = "INSERT INTO pending_reservations (academic_id, student_id, student_name, student_number, reservation_reason, reservation_details)
              VALUES ('$academic_id', '$student_id', '$student_name', '$student_number', '$reservation_reason', '$reservation_details')";

    if ($connection_academics->query($query) === TRUE) {
        echo "Reservation request sent successfully!";
    } else {
        echo "Error: " . $query . "<br>" . $connection_academics->error;
    }
} else {
    echo "Invalid request method.";
}
?>
