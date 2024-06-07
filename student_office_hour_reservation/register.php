<?php
session_start();
require_once('config.php');

if(isset($_POST['register'])){
    $full_name = $_POST['full_name'];
    $student_number = $_POST['student_number'];
    $email = $_POST['email'];
    $whatsapp_number = $_POST['whatsapp_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $username = $student_number; // Benzersiz bir kullanıcı adı atayın

    // Check if email is from @std.neu.edu.tr domain
    if(strpos($email, '@std.neu.edu.tr') !== false){
        $query = "INSERT INTO students (full_name, student_number, email, whatsapp_number, password, username) VALUES ('$full_name', '$student_number', '$email', '$whatsapp_number', '$password', '$username')";
        if($connection->query($query) === TRUE){
            $_SESSION['student_id'] = $connection->insert_id;
            header("Location: index.php");
        } else {
            $error = "An error occurred during registration: " . $connection->error;
        }
    } else {
        $error = "Only @std.neu.edu.tr email addresses are allowed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"]{
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
        <h1>Register</h1>
        <?php if(isset($error)) echo "<p>$error</p>"; ?>
        <form action="" method="post">
            <input type="text" name="full_name" placeholder="Full Name" required><br>
            <input type="text" name="student_number" placeholder="Student Number" required><br>
            <input type="email" name="email" placeholder="Email Address" required><br>
            <input type="text" name="whatsapp_number" placeholder="Whatsapp Number" required><br>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            <input type="submit" name="register" value="Register">
        </form>
        <p><a href="index.php">Login</a> | <a href="forgot_password.php">Forgot Password</a></p>
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
