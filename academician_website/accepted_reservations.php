<?php
session_start();
require_once('config_db.php');

if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

$academic_id = $_SESSION['academic_id'];

// Accepted rezervasyonlarını al
$query = "SELECT ar.*, s.full_name, s.student_number, s.profile_picture FROM form_db.accepted_reservations ar 
          JOIN students_database.students s ON ar.student_id = s.id 
          WHERE ar.academic_id = '$academic_id'";
$result = $connection_academics->query($query);

$days_of_week = [
    "1" => "Monday", 
    "2" => "Tuesday", 
    "3" => "Wednesday", 
    "4" => "Thursday", 
    "5" => "Friday", 
    "6" => "Saturday"
];

$times_of_day = [
    "09:00 - 09:50", "10:00 - 10:50", "11:00 - 11:50", "12:00 - 12:50", 
    "13:00 - 13:50", "14:00 - 14:50", "15:00 - 15:50", "16:00 - 16:50", 
    "17:00 - 17:50", "18:00 - 18:50", "19:00 - 19:50"
];
// Pending rezervasyonlarının sayısını al
$query_pending_count = "SELECT COUNT(*) as count FROM form_db.pending_reservations WHERE academic_id = '$academic_id'";
$result_pending_count = $connection_academics->query($query_pending_count);
$row_pending_count = $result_pending_count->fetch_assoc();
$pending_count = $row_pending_count['count'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Reservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('background_img.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        body::after {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .navbar a.logo-link {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .navbar img {
            width: 200px;
            height: auto;
            margin-right: 10px;
        }
        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar ul li {
            margin-right: 10px;
        }
        .navbar ul li a {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
            background-color: #FF0000;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin-top: 120px; /* Adjusted margin-top to position below navbar */
        }
        .title-container {
            background-color: white;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            color: black;
            margin: 0;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: black;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px 0;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .student-container img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .student-container {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php" class="logo-link">
            <img src="neu-logo.png" alt="NEU Logo">
        </a>
        <ul>
            <li><a href="index.php" class="logout-btn">Log Out</a></li>
            <li><a href="accepted_reservations.php" class="accepted-reservations-btn">Accepted Reservations</a></li>
            <li><a href="pending_reservations.php" class="pending-reservations-btn">Pending Reservations | <?php echo $pending_count; ?></a></li>            <li><a href="timetable.php" class="timetable-btn">Timetable</a></li>
            <li><a href="profile.php" class="profile-btn">Profile</a></li>
            <li><a href="settings.php" class="settings-btn">Settings</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="title-container">
            <h1>Accepted Reservations</h1>
        </div>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Student Number</th>
                <th>Reservation Reason</th>
                <th>Reservation Time</th>
                <th>Reservation Status in the Table</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { 
                $student_name = $row['full_name'];
                $student_number = $row['student_number'];
                $reservation_reason = $row['reservation_reason'];
                $reservation_day = isset($days_of_week[$row['reservation_day']]) ? $days_of_week[$row['reservation_day']] : $row['reservation_day'];
                $reservation_time = $row['reservation_time'];
                $profile_picture = $row['profile_picture'];
            ?>
                <tr>
                    <td class="student-container">
                        <?php 
                        if (!empty($profile_picture)) {
                            echo '<img src="../student_office_hour_reservation/uploads/' . $profile_picture . '" alt="Profile Picture">';
                        } else {
                            echo '<img src="../student_office_hour_reservation/default_profile_picture.png" alt="Default Profile Picture">';
                        }
                        ?>
                        <?php echo $student_name; ?>
                    </td>
                    <td><?php echo $student_number; ?></td>
                    <td><?php echo $reservation_reason; ?></td>
                    <td><?php echo $reservation_day; ?></td>
                    <td><?php echo $reservation_time; ?></td>
                    <td>
                        <button class="btn" onclick="showDetails(`<?php echo htmlspecialchars($row['reservation_details']); ?>`)">View Details</button>
                        <a href="remove_reservation.php?id=<?php echo $row['id']; ?>" class="btn">Remove</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <pre id="detailsText"></pre>
        </div>
    </div>

    <script>
        function showDetails(details) {
            document.getElementById('detailsText').innerHTML = details;
            document.getElementById('detailsModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailsModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
