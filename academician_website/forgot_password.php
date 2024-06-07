<?php
session_start();
require_once('config_db.php');

if(isset($_POST['reset'])){
    $email = $_POST['email'];

    // Veritabanında e-posta adresini kontrol et
    $query = "SELECT * FROM academics WHERE email = '$email'";
    $result = $connection_academics->query($query);

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        // Rastgele bir şifre oluştur
        $new_password = generateRandomPassword();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Kullanıcının şifresini veritabanında güncelle
        $update_query = "UPDATE academics SET password = '$hashed_password' WHERE email = '$email'";
        if($connection_academics->query($update_query) === TRUE){
            // Yeni şifreyi kullanıcının e-posta adresine gönder (Bu kısmı uygulamanız gerekecek)
            $to = $email;
            $subject = "Password Reset";
            $message = "Your new password is: $new_password";
            // mail($to, $subject, $message); // E-posta gönderim fonksiyonunu etkinleştirin

            $success = "Your password has been reset. Check your email for the new password.";
        } else {
            $error = "An error occurred while resetting your password: " . $connection_academics->error;
        }
    } else {
        $error = "User not found!";
    }
}

function generateRandomPassword($length = 8){
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for($i = 0; $i < $length; $i++){
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        input[type="email"],
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
            background-color: #FF0000;
        }
        a {
            display: inline-block;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            color: #FF0000;
            text-decoration: none;
        }
        a:hover {
            background-color: rgba(255, 0, 0, 0.1);
        }
        p {
            text-align: center;
            margin-top: 10px;
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
        <h1>Forgot Password</h1>
        <?php
        if (!empty($error)) {
            echo "<script>showModal('Error', '$error');</script>";
        }
        if (!empty($success)) {
            echo "<script>showModal('Success', '$success');</script>";
        }
        ?>
        <form action="forgot_password.php" method="post">
            <input type="email" name="email" placeholder="Email Address" required><br>
            <input type="submit" name="reset" value="Reset Password">
        </form>
        <p><a href="index.php">Login</a> | <a href="register.php">Register</a></p>
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
            <?php if (!empty($error)) : ?>
                showModal('Error', '<?php echo $error; ?>');
            <?php endif; ?>
            <?php if (!empty($success)) : ?>
                showModal('Success', '<?php echo $success; ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>
