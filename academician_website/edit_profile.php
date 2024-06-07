<?php
session_start();
require_once('config_db.php');

// Kullanıcının oturum açıp açmadığını kontrol et
if(!isset($_SESSION['academic_id'])){
    header("Location: login.php");
    exit;
}

// Kullanıcının oturum açmış olduğunu varsayarak, profil bilgilerini al
$academic_id = $_SESSION['academic_id'];
$query = "SELECT * FROM academics WHERE id = '$academic_id'";
$result = $connection_academics->query($query);

if($result->num_rows > 0){
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

// Profil resmi kaldırma işlemi
if (isset($_POST['remove_picture'])) {
    $profile_picture = $user['profile_picture'];
    if ($profile_picture) {
        unlink("uploads/$profile_picture"); // Dosyayı sistemden kaldır
        $connection_academics->query("UPDATE academics SET profile_picture = NULL WHERE id = '$academic_id'");
        $user['profile_picture'] = NULL; // Kullanıcı bilgisini güncelle
    }
}

// Form gönderildiğinde
if(isset($_POST['update'])){
    $full_name = $_POST['full_name'];
    $title = $_POST['title'];
    $email = $_POST['email'];
    $whatsapp_number = $_POST['whatsapp_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Şifre doğrulaması
    if(!empty($password) && $password != $confirm_password){
        $error = "Passwords do not match!";
    } else {
        // Şifreyi hashle
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

        // Profil resmi yükleme işlemi
        $profile_picture = $user['profile_picture'];
        if ($_FILES['profile_picture']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

            // Eski profil resmini kaldır
            if ($profile_picture) {
                unlink("uploads/$profile_picture");
            }
            $profile_picture = $_FILES['profile_picture']['name'];
        }

        // Profil bilgilerini güncelle
        $update_query = "UPDATE academics SET firstname = '$full_name', lastname = '', title = '$title', email = '$email', whatsapp = '$whatsapp_number', password = '$hashed_password', profile_picture = '$profile_picture' WHERE id = '$academic_id'";
        if($connection_academics->query($update_query) === TRUE){
            $success = "Profile updated successfully!";
            header("Location: profile.php");
            exit;
        } else {
            $error = "An error occurred while updating profile: " . $connection_academics->error;
        }
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
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background_img.jpg');
            background-size: cover;
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
            background: rgba(0, 0, 0, 0.5); /* Yarı saydam siyah katman */
            z-index: -1; /* İçerikten geride */
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"], .cancel-btn, .remove-picture-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        input[type="submit"]:hover, .cancel-btn:hover, .remove-picture-btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }
        .success {
            color: #28a745;
            margin-bottom: 10px;
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
        .navbar ul li a.logout-btn,
        .navbar ul li a.accepted-reservations-btn,
        .navbar ul li a.pending-reservations-btn,
        .navbar ul li a.timetable-btn,
        .navbar ul li a.profile-btn,
        .navbar ul li a.settings-btn {
            background-color: #FF0000; /* Kırmızı */
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
        .profile-picture-preview {
            display: block;
            margin: 10px auto;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
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
        <h1>Edit Profile</h1>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $user['firstname']; ?>" required>
            </div>
            <div class="form-group">
                <label for="title">Academic Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $user['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="whatsapp_number">Whatsapp Number:</label>
                <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?php echo $user['whatsapp']; ?>" required>
            </div>
            <div class="form-group password-container">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password">
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            <div class="form-group password-container">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" onchange="showPreview(event)">
                <?php if ($user['profile_picture']) { ?>
                    <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture Preview" class="profile-picture-preview" id="profilePicturePreview">
                <?php } ?>
            </div>
            <?php if ($user['profile_picture']) { ?>
                <div class="form-group">
                    <button type="submit" name="remove_picture" class="remove-picture-btn">Remove Profile Picture</button>
                </div>
            <?php } ?>
            <div class="form-group">
                <input type="submit" name="update" value="Update Profile">
                <a href="profile.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
        <div id="errorMessage" class="error"></div>
        <div id="successMessage" class="success"></div>
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

        document.querySelector('form').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (password && password !== confirmPassword) {
                event.preventDefault(); // Formun gönderilmesini engelle
                document.getElementById('errorMessage').textContent = 'Passwords do not match!';
            } else {
                document.getElementById('errorMessage').textContent = '';
            }
        });

        function showPreview(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById('profilePicturePreview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'profilePicturePreview';
                    preview.className = 'profile-picture-preview';
                    input.parentNode.appendChild(preview);
                }
                preview.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</body>
</html>
