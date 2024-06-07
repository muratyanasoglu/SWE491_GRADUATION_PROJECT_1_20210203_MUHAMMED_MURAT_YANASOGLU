<?php
// Veritabanı bağlantı dosyasını dahil edin
require_once('config_db.php');

// Hata mesajlarını saklamak için bir dizi oluşturun
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form verilerini alın ve güvenli hale getirin
    $firstname = $connection_academics->real_escape_string($_POST['firstname']);
    $lastname = $connection_academics->real_escape_string($_POST['lastname']);
    $title = $connection_academics->real_escape_string($_POST['title']);
    $email = $connection_academics->real_escape_string($_POST['email']);
    $whatsapp = $connection_academics->real_escape_string($_POST['whatsapp']);
    $password = password_hash($connection_academics->real_escape_string($_POST['password']), PASSWORD_BCRYPT); // Şifreyi hashleyin

    // E-posta doğrulama
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@neu\.edu\.tr$/', $email)) {
        $errors[] = "Invalid email address. Please use your @neu.edu.tr email.";
    }

    // Aynı isim ve soyadın olup olmadığını kontrol et
    $query_name_check = "SELECT * FROM academics WHERE firstname='$firstname' AND lastname='$lastname'";
    $result_name_check = $connection_academics->query($query_name_check);
    if ($result_name_check->num_rows > 0) {
        $errors[] = "An academician with the same name already exists.";
    }

    // Aynı e-posta adresinin olup olmadığını kontrol et
    $query_email_check = "SELECT * FROM academics WHERE email='$email'";
    $result_email_check = $connection_academics->query($query_email_check);
    if ($result_email_check->num_rows > 0) {
        $errors[] = "An academician with the same email already exists.";
    }

    // Hata yoksa kayıt işlemini gerçekleştir
    if (empty($errors)) {
        $query = "INSERT INTO academics (firstname, lastname, title, email, whatsapp, password) 
                  VALUES ('$firstname', '$lastname', '$title', '$email', '$whatsapp', '$password')";
        if ($connection_academics->query($query) === TRUE) {
            echo "<script>showModal('Success', 'Registration successful!');</script>";
        } else {
            $errors[] = "Error: " . $query . "<br>" . $connection_academics->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Profile Registration</title>
    <link rel="stylesheet" type="text/css" href="form_page_design.css">
    <style>
        .password-container {
            position: relative;
            width: 90%;
            margin: 0 auto 10px auto;
        }

        .password-container input {
            width: 90%;
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

        a {
            display: inline-block;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            color: #FF0000; /* Kırmızı */
            text-decoration: none;
        }

        a:hover {
            background-color: rgba(255, 0, 0, 0.1); /* Kırmızının açık tonu */
        }

        .form-links {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php
    // Hata mesajları varsa göster
    if (!empty($errors)) {
        echo "<script>showModal('Error', '" . implode('<br>', $errors) . "');</script>";
    }
    ?>

    <form action="register.php" method="post" onsubmit="return validateEmail()">
        <img src="neu-logo.png" alt="University Logo" class="university-logo">
        <h1>Academicians Profile Registration</h1>
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br>
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br>
        <label for="title">Academic Title:</label>
        <select id="title" name="title">
            <option value="Prof">Professor</option>
            <option value="AsstProf">Assistant Professor</option>
            <option value="Phd">PhD</option>
            <option value="Msc">Master of Science</option>
            <option value="Bachelor">Bachelor</option>
            <option value="Instructor">Instructor</option>
        </select><br>
        <label for="email">University Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="whatsapp">WhatsApp Number:</label>
        <input type="tel" id="whatsapp" name="whatsapp" required><br>
        <label for="password">Password:</label>
        <div class="password-container">
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <input type="submit" value="Register">
        <div class="form-links">
            <a href="index.php">Login</a> | <a href="forgot_password.php">Forgot Password</a>
        </div>
    </form>

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

        function validateEmail() {
            var emailInput = document.getElementById('email').value;
            var emailPattern = /@neu\.edu\.tr$/;
            if (!emailPattern.test(emailInput)) {
                showModal('Error', 'Invalid email address. Please use your @neu.edu.tr email.');
                return false;
            }
            return true;
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
