<?php
session_start();
require_once('config.php');

// Kullanıcı oturumu kontrolü
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// Kullanıcının hesabını kaldırma işlemi
if (isset($_POST['delete_account'])) {
    // Hesabı veritabanından sil
    $delete_query = "DELETE FROM students WHERE id = '$student_id'";
    if ($connection->query($delete_query) === TRUE) {
        // Oturumu sonlandır ve ana sayfaya yönlendir
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        $error = "An error occurred while deleting the account: " . $connection->error;
    }
}

// Kullanıcının şifresini değiştirme işlemi
if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Şifreyi veritabanında güncelle
    $update_query = "UPDATE students SET password = '$new_password' WHERE id = '$student_id'";
    if ($connection->query($update_query) === TRUE) {
        $success = "Password updated successfully!";
    } else {
        $error = "An error occurred while updating the password: " . $connection->error;
    }
}

// Contact Us form işlemi
if (isset($_POST['contact_submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // E-posta gönderimi
    $to = "akademiyanasoglu@gmail.com";
    $subject = "Contact Form Submission";
    $email_body = "Name: $name\n";
    $email_body .= "Email: $email\n\n";
    $email_body .= "Message:\n$message";

    if (mail($to, $subject, $email_body)) {
        $success = "Your message has been sent successfully!";
    } else {
        $error = "An error occurred while sending the message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <style>
        body {
            background-image: url('background_img.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Saydam arka plan */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h1, h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #ff0000;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #cc0000;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        .navbar ul li a.logout-btn {
            background-color: #FF0000;
        }
        .navbar ul li a.home-btn {
            background-color: #FF0000;
        }
        .navbar ul li a.settings-btn {
            background-color: #FF0000;
        }
        .navbar ul li a.profile-btn {
            background-color: #FF0000;
        }
        .navbar ul li a.reservations-btn {
            background-color: #FF0000;
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            width: calc(100% - 40px); /* Göz simgesi için yer bırak */
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
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
            <li><a href="settings.php" class="reservations-btn">Settings</a></li>
            <li><a href="profile.php" class="settings-btn">Profile</a></li>
            <li><a href="student_pending_reservations.php" class="reservations-btn">Pending Reservations</a></li>
        <li><a href="student_accepted_reservations.php" class="reservations-btn">Accepted Reservations</a></li>
            <li><a href="home.php" class="home-btn">Home</a></li>
            <!-- <li><a href="profile.php" class="profile-btn">Profile</a></li> -->
        </ul>
    </div>
    <!-- Settings Container -->
    <div class="container">
        <h1>Settings</h1>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <?php if (isset($success)) echo "<p>$success</p>"; ?>
        <!-- Delete Account Form -->
        <form action="" method="post" onsubmit="return confirmDelete()">
            <input type="submit" name="delete_account" value="Delete Account">
        </form>

         <!-- Change Password Form -->
         <form action="" method="post" onsubmit="return validateForm()">
            <div class="password-container">
                <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
                <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
            <input type="submit" name="change_password" value="Change Password">
        </form>
    </div>

    <!-- Contact Us Container -->
    <div class="container">
        <h2>Contact Us</h2>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Your Name" required><br>
            <input type="email" name="email" placeholder="Your Email" required><br>
            <textarea name="message" placeholder="Your Message" rows="4" required></textarea><br>
            <input type="submit" name="contact_submit" value="Send Message">
        </form>
    </div>

   
    <script>
        function validateForm() {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
        function confirmDelete() {
            return confirm("Are you sure you want to delete your account?");
        }

        function goToHome() {
        window.location.href = "home.php";
    }

        function togglePassword(fieldId) {
            var passwordField = document.getElementById(fieldId);
            var toggleIcon = passwordField.nextElementSibling;
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>
</body>
</html>
