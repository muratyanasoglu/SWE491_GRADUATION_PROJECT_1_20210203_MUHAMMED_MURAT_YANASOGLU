<?php
session_start();
require_once('config.php');

if(isset($_POST['reset'])){
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM students WHERE email = '$email'";
    $result = $connection->query($query);

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        // Generate a random password
        $new_password = generateRandomPassword();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user's password in database
        $update_query = "UPDATE students SET password = '$hashed_password' WHERE email = '$email'";
        if($connection->query($update_query) === TRUE){
            // Send the new password to user's email (you need to implement this part)
            $to = $email;
            $subject = "Password Reset";
            $message = "Your new password is: $new_password";
            // Uncomment the following line when you implement the email sending functionality
            // mail($to, $subject, $message);
            
            $success = "Your password has been reset. Check your email for the new password.";
        } else {
            $error = "An error occurred while resetting your password: " . $connection->error;
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
        input[type="email"]
         {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <?php if(isset($error)) echo "<p>$error</p>"; ?>
        <?php if(isset($success)) echo "<p>$success</p>"; ?>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Email Address" required><br>
            <input type="submit" name="reset" value="Reset Password">
        </form>
        <p><a href="index.php">Login</a> | <a href="register.php">Register</a></p>
    </div>
</body>
</html>
