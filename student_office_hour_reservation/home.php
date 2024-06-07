<?php
session_start();

// Students veritabanı için config dosyası
require_once('config.php');

// Academics ve Timetable veritabanı için config dosyası
require_once('config_db.php');

// Giriş yapmış kullanıcıyı al
$student_id = $_SESSION['student_id'];

// Students veritabanından kullanıcı bilgilerini al
$query_student = "SELECT * FROM students WHERE id = '$student_id'";
$result_student = $connection->query($query_student);

if ($result_student->num_rows > 0) {
    $user_data = $result_student->fetch_assoc();
    $full_name = $user_data['full_name']; // Düzeltme: full_name alanını kullan
} else {
    // Kullanıcı bulunamadıysa, oturumu kapat ve login sayfasına yönlendir
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="home.css">
    <style>
        /* Stil eklemeleri burada yapılabilir */
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
            <li><a href="student_pending_reservations.php" class="reservations-btn">Pending Reservations</a></li>
            <li><a href="student_accepted_reservations.php" class="reservations-btn">Accepted Reservations</a></li>
            <li><a href="settings.php" class="settings-btn">Settings</a></li>
            <li><a href="home.php" class="home-btn">Home</a></li>
            <li><a href="profile.php" class="profile-btn">Profile</a></li>
        </ul>
    </div>

    <!-- Ana içerik -->
    <div class="container">
        <h1>Welcome, <?php echo $full_name; ?>!</h1>

        <!-- Arama formu -->
        <form action="list_of_timetables.php" method="post">
            <input type="text" name="search_query" placeholder="Enter academician name or surname" required>
            <input type="submit" name="search" value="Search">
        </form>
    </div>
</body>
</html>
