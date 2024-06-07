<?php
session_start();
require_once('config_db.php');

// Kullanıcı oturumu kontrolü
if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

$academic_id = $_SESSION['academic_id'];

// Veritabanından kullanıcı bilgilerini al
$query = "SELECT * FROM academics WHERE id = '$academic_id'";
$result = $connection_academics->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $full_name = $user['firstname'];
    $title = $user['title'];
    $email = $user['email'];
    $whatsapp_number = $user['whatsapp'];
    $profile_picture = $user['profile_picture'];
} else {
    echo "User not found!";
    exit;
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
    <title>Profile</title>
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
            background: rgba(0, 0, 0, 0.5); /* Yarı saydam siyah katman */
            z-index: -1; /* İçerikten geride */
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
        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        .profile-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-info p {
            margin: 10px 0;
            font-size: 18px;
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
        .edit-profile-btn {
            text-decoration: none;
            color: #fff;
            background-color: #FF0000;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            margin: 0 auto;
        }
        h1 {
            margin-bottom: 20px;
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
            <li><a href="pending_reservations.php" class="pending-reservations-btn">Pending Reservations | <?php echo $pending_count; ?></a></li>             <li><a href="timetable.php" class="timetable-btn">Timetable</a></li>
            <li><a href="profile.php" class="profile-btn">Profile</a></li>
            <li><a href="settings.php" class="settings-btn">Settings</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Profile</h1>
        <?php if ($profile_picture) { ?>
            <img src="uploads/<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-picture">
        <?php } ?>
        <div class="profile-info">
            <p><strong>Full Name:</strong> <?php echo $title . ' ' . $full_name; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Whatsapp Number:</strong> <?php echo $whatsapp_number; ?></p>
        </div>
        <a href="edit_profile.php" class="edit-profile-btn">Edit Profile</a>
    </div>
</body>
</html>
