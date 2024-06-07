<?php
session_start();
require_once('config_db.php');
require_once('config_students.php');

if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

$academic_id = $_SESSION['academic_id'];

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Akademik ismini al
    $query_academic = "SELECT * FROM academics WHERE id = '$academic_id'";
    $result_academic = $connection_academics->query($query_academic);
    if ($result_academic->num_rows > 0) {
        $academic = $result_academic->fetch_assoc();
        $academic_name = $academic['title'] . ' ' . $academic['firstname'] . ' ' . $academic['lastname'];
    } else {
        die("Academic not found.");
    }

    // Rezervasyonu kabul et ve accepted_reservations tablosuna taşı
    $query = "INSERT INTO form_db.accepted_reservations (academic_id, academic_name, student_id, student_name, student_number, reservation_day, reservation_time, reservation_reason, reservation_details)
              SELECT academic_id, '$academic_name', student_id, student_name, student_number, reservation_day, reservation_time, reservation_reason, reservation_details
              FROM form_db.pending_reservations WHERE id = '$reservation_id'";

    if ($connection_academics->query($query) === TRUE) {
        // Pending rezervasyonunu sil
        $delete_query = "DELETE FROM form_db.pending_reservations WHERE id = '$reservation_id'";
        $connection_academics->query($delete_query);

        // Öğrenciye e-posta bildirimi gönder
        $query_student = "SELECT email FROM students WHERE id = (SELECT student_id FROM form_db.accepted_reservations WHERE id = (SELECT MAX(id) FROM form_db.accepted_reservations))";
        $result_student = $connection_students->query($query_student);
        if ($result_student->num_rows > 0) {
            $student = $result_student->fetch_assoc();
            $to = $student['email'];
            $subject = "Reservation Accepted";
            $message = "Your reservation request has been accepted.";
            mail($to, $subject, $message);
        }

        header("Location: pending_reservations.php");
    } else {
        echo "Error: " . $connection_academics->error;
    }
}
?>
