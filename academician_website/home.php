<?php
session_start();
require_once('config_db.php');

// Giriş yapmış kullanıcıyı al
$academic_id = $_SESSION['academic_id'];

// Academics veritabanından kullanıcı bilgilerini al
$query_academic = "SELECT * FROM academics WHERE id = '$academic_id'";
$result_academic = $connection_academics->query($query_academic);

if ($result_academic->num_rows > 0) {
    $user_data = $result_academic->fetch_assoc();
    $full_name = $user_data['firstname'] . ' ' . $user_data['lastname']; // Tam adı birleştir
    $title = $user_data['title']; // Akademik unvanı al
} else {
    // Kullanıcı bulunamadıysa, oturumu kapat ve login sayfasına yönlendir
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

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
    <title>Home</title>
    <style>
        body {
            background-image: url('background_img.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            list-style-type: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .navbar ul li {
            margin-right: 10px;
        }
        .navbar ul li a {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
        }
        .navbar ul li a.logout-btn,
        .navbar ul li a.accepted-reservations-btn,
        .navbar ul li a.pending-reservations-btn,
        .navbar ul li a.timetable-btn,
        .navbar ul li a.profile-btn,
        .navbar ul li a.settings-btn {
            background-color: #FF0000; /* Kırmızı */
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h1 {
            color: #000; /* Siyah */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="home.php" class="logo-link">
            <img src="neu-logo.png" alt="NEU Logo">
        </a>
        <ul>
            <li><a href="index.php" class="logout-btn">Log Out</a></li>
            <li><a href="accepted_reservations.php" class="accepted-reservations-btn">Accepted Reservations</a></li>
            <li><a href="pending_reservations.php" class="pending-reservations-btn">Pending Reservations | <?php echo $pending_count; ?></a></li>
            <li><a href="timetable.php" class="timetable-btn">Timetable</a></li>
            <li><a href="profile.php" class="profile-btn">Profile</a></li>
            <li><a href="settings.php" class="settings-btn">Settings</a></li>
        </ul>
    </div>

    <!-- Ana içerik -->
    <div class="container">
        <h1>Welcome, <?php echo $title . ' ' . $full_name; ?>!</h1>
    </div>
</body>
</html>
