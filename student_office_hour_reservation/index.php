<?php
session_start();
require_once('config.php');

// Eğer login formu gönderildiyse
if(isset($_POST['login'])){
    $student_number = $_POST['student_number'];
    $password = $_POST['password'];

    // Öğrenci numarası ve şifreyi kontrol etmek için veritabanına sorgu gönder
    $query = "SELECT * FROM students WHERE student_number = '$student_number'";
    $result = $connection->query($query);

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        // Girilen şifre ile veritabanındaki şifreyi karşılaştır
        if(password_verify($password, $user['password'])){
            $_SESSION['student_id'] = $user['id']; // Oturumu başlat
            header("Location: home.php"); // Anasayfaya yönlendir
        } else {
            $error = "Student number or password is incorrect!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome To Student Office Hour Reservation Website</title>
    <style>
        body {
            background-image: url('background_img.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }
        /* Arka plan efekti için ek katman */
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
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            cursor: pointer;
            color: white;
            border: none;
            border-radius: 5px;
            background-color: #FF0000; /* Kırmızı */
        }
        a {
            display: inline-block;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            color: #FF0000; /* Kırmızı */
            text-decoration: none;
        }
        a:hover {
            background-color: rgba(255, 0, 0, 0.1); /* Kırmızının açık tonu */
        }
        p {
            text-align: center;
            margin-top: 10px;
        }
        img.logo {
            width: 150px; /* Logo boyutu */
            display: block;
            margin: 0 auto;
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            width: calc(97% - 40px); /* Göz simgesi için yer bırak */
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
    <div class="container">
        <img src="neu-logo.png" alt="NEU Logo" class="logo">
        <h1>Welcome To Student Office Hour Reservation Website</h1>
        <?php if(isset($error)) echo "<p>$error</p>"; ?>
        <form action="" method="post">
            <input type="text" name="student_number" placeholder="Student Number" required><br>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            <input type="submit" name="login" value="Login">
        </form>
        <p><a href="register.php">Register</a> | <a href="forgot_password.php">Forgot Password</a></p>
    </div>
    <script>
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
