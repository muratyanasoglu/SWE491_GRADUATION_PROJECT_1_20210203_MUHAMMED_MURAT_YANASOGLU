<?php
session_start();
require_once('config.php');
require_once('../academician_website/config_db.php');

$response = ['success' => false, 'error' => ''];

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    $query_student = "SELECT * FROM students WHERE id = '$student_id'";
    $result_student = $connection->query($query_student);

    if ($result_student->num_rows > 0) {
        $student = $result_student->fetch_assoc();
        $student_name = $student['full_name'];
        $student_number = $student['student_number'];
        $academic_id = $_POST['academic_id'];
        $reservation_day = $_POST['reservation_day'];
        $reservation_time = $_POST['reservation_time'];
        $reservation_reason = $_POST['reservation_reason'];
        $reservation_details = $_POST['reservation_details'];

        $query = "INSERT INTO pending_reservations (academic_id, student_id, student_name, student_number, reservation_day, reservation_time, reservation_reason, reservation_details)
                  VALUES ('$academic_id', '$student_id', '$student_name', '$student_number', '$reservation_day', '$reservation_time', '$reservation_reason', '$reservation_details')";

        if ($connection_academics->query($query) === TRUE) {
            $response['success'] = true;
        } else {
            $response['error'] = "Database error: " . $connection_academics->error;
        }
    } else {
        $response['error'] = "Student not found.";
    }
} else {
    $response['error'] = "User not logged in.";
}

echo json_encode($response);
?>
