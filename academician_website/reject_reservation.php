<?php
session_start();
require_once('config_db.php');

if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

$academic_id = $_SESSION['academic_id'];

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Pending reservations tablosundan sil
    $delete_query = "DELETE FROM pending_reservations WHERE id = '$reservation_id'";

    if ($connection_academics->query($delete_query) === TRUE) {
        header("Location: pending_reservations.php");
        exit;
    } else {
        echo "Error: " . $delete_query . "<br>" . $connection_academics->error;
    }
} else {
    echo "Invalid reservation ID.";
}
?>
