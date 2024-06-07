<?php
session_start();
require_once('config_db.php');

// Eğer kullanıcı giriş yapmışsa, home.php'ye yönlendir
// if (isset($_SESSION['academic_id'])) {
//     header("Location: home.php");
//     exit();
// }

// Hata mesajlarını saklamak için bir dizi oluşturun
$errors = [];

// Eğer login formu gönderildiyse
if (isset($_POST['index'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Email ve şifreyi kontrol etmek için veritabanına sorgu gönder
    $query = "SELECT * FROM academics WHERE email = '$email'";
    $result = $connection_academics->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Girilen şifre ile veritabanındaki şifreyi karşılaştır
        if (password_verify($password, $user['password'])) {
            $_SESSION['academic_id'] = $user['id']; // Oturumu başlat
            header("Location: home.php"); // Anasayfaya yönlendir
            exit();
        } else {
            $errors[] = "Email or password is incorrect!";
        }
    } else {
        $errors[] = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome To Student Office Hour Reservation Website For Academicians</title>
    <style>
        body {
            background-image: url('background_img.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
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
            width: 150px;
            display: block;
            margin: 0 auto;
        }
        .password-container {
            position: relative;
            width: 87%;
        }
        .password-container input {
            width: 100%;
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        /* Modal Stilleri */
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
    <div class="container">
        <img src="neu-logo.png" alt="NEU Logo" class="logo">
        <h1>Welcome To Student Office Hour Reservation Website For Academicians</h1>
        <?php
        if (!empty($errors)) {
            echo "<script>showModal('Error', '" . implode('<br>', $errors) . "');</script>";
        }
        ?>
        <form action="index.php" method="post">
            <input type="text" name="email" placeholder="University Email" required><br>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
            <input type="submit" name="index" value="Login">
        </form>
        <p><a href="register.php">Register</a> | <a href="forgot_password.php">Forgot Password</a></p>
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
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }

        function showModal(header, message) {
            document.getElementById('modalHeader').textContent = header;
            document.getElementById('modalMessage').innerHTML = message;
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        // PHP tarafından tetiklenen modal scriptini çalıştırmak için
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($errors)) : ?>
                showModal('Error', '<?php echo implode('<br>', $errors); ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>
