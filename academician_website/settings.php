<?php
session_start();
require_once('config_db.php');
require 'vendor/autoload.php'; // PHPMailer autoload dosyasını dahil edin

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kullanıcı oturumu kontrolü
if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

$academic_id = $_SESSION['academic_id'];

// Kullanıcının hesabını kaldırma işlemi
if (isset($_POST['delete_account'])) {
    // Hesabı veritabanından sil
    $delete_query = "DELETE FROM academics WHERE id = '$academic_id'";
    if ($connection_academics->query($delete_query) === TRUE) {
        // Oturumu sonlandır ve ana sayfaya yönlendir
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        $error = "An error occurred while deleting the account: " . $connection_academics->error;
    }
}

// Kullanıcının şifresini değiştirme işlemi
if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Şifreyi veritabanında güncelle
    $update_query = "UPDATE academics SET password = '$new_password' WHERE id = '$academic_id'";
    if ($connection_academics->query($update_query) === TRUE) {
        $success = "Password updated successfully!";
    } else {
        $error = "An error occurred while updating the password: " . $connection_academics->error;
    }
}

// Contact Us form işlemi
if (isset($_POST['contact_submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // PHPMailer ayarları
    $mail = new PHPMailer(true);

    try {
        // Sunucu ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP sunucu adresi
        $mail->SMTPAuth = true;
        $mail->Username = 'neareastuniversitytrnc@gmail.com'; // SMTP kullanıcı adı
        $mail->Password = 'neareastuniversitytrnc1988'; // SMTP şifresi
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Alıcılar
        $mail->setFrom($email, $name);
        $mail->addAddress('20210203@std.neu.edu.tr'); // Mesajın gönderileceği adres

        // İçerik
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';
        $mail->Body    = nl2br("Name: $name\nEmail: $email\n\nMessage:\n$message");
        $mail->AltBody = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        $mail->send();
        $success = 'Your message has been sent successfully!';
    } catch (Exception $e) {
        $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            text-align: center;
        }
        .modal-header {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .modal-footer {
            margin-top: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
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
            <li><a href="logout.php" class="logout-btn">Log Out</a></li>
            <li><a href="accepted_reservations.php" class="accepted-reservations-btn">Accepted Reservations</a></li>
            <li><a href="pending_reservations.php" class="pending-reservations-btn">Pending Reservations | <?php echo $pending_count; ?></a></li>             <li><a href="timetable.php" class="timetable-btn">Timetable</a></li>
            <li><a href="profile.php" class="profile-btn">Profile</a></li>
            <li><a href="home.php" class="settings-btn">Home</a></li>
        </ul>
    </div>
    <!-- Settings Container -->
    <div class="container">
        <h1>Settings</h1>
        <?php if (isset($error)) echo "<script>showModal('Error', '$error');</script>"; ?>
        <?php if (isset($success)) echo "<script>showModal('Success', '$success');</script>"; ?>
        <!-- Delete Account Form -->
        <form action="settings.php" method="post" onsubmit="return confirmDelete()">
            <input type="submit" name="delete_account" value="Delete Account">
        </form>

        <!-- Change Password Form -->
        <form action="settings.php" method="post" onsubmit="return validateForm()">
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
        <form action="settings.php" method="post">
            <input type="text" name="name" placeholder="Your Name" required><br>
            <input type="email" name="email" placeholder="Your Email" required><br>
            <textarea name="message" placeholder="Your Message" rows="4" required></textarea><br>
            <input type="submit" name="contact_submit" value="Send Message">
        </form>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-header" id="modalHeader"></div>
            <div id="modalMessage"></div>
            <div class="modal-footer">
                <button onclick="closeModal()">OK</button>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                showModal('Error', 'Passwords do not match!');
                return false;
            }
            return true;
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete your account?");
        }

        function showModal(header, message) {
            document.getElementById('modalHeader').textContent = header;
            document.getElementById('modalMessage').innerHTML = message;
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
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

        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($error)) : ?>
                showModal('Error', '<?php echo $error; ?>');
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                showModal('Success', '<?php echo $success; ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>
