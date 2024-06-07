<?php
session_start();

// Academics ve Timetable veritabanı için config dosyası
require_once('config_db.php');

if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];

    // Ad ve soyadı birleştirerek arama sorgusunu hazırlayın
    $query_academics = "
        SELECT * FROM academics 
        WHERE CONCAT(firstname, ' ', lastname) LIKE '%$search_query%' 
        OR firstname LIKE '%$search_query%' 
        OR lastname LIKE '%$search_query%'
    ";
    $result_academics = $connection_academics->query($query_academics);

    // Hata ayıklama: sorguyu ve sonucu ekrana yazdırın
    if ($result_academics === false) {
        die("SQL Error: " . $connection_academics->error);
    }

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Search Results</title>';
    echo '<link rel="stylesheet" type="text/css" href="styles.css">'; // External CSS
    echo '<style>';
    echo 'body {';
    echo '    position: relative;';
    echo '    min-height: 100vh;';
    echo '    margin: 0;';
    echo '    background-image: url("home_background_img.jpg");';
    echo '    background-size: cover;';
    echo '    background-position: center;';
    echo '    font-family: Arial, sans-serif;';
    echo '    color: #333;';
    echo '}';
    echo 'body::after {';
    echo '    content: "";';
    echo '    display: block;';
    echo '    position: fixed;';
    echo '    top: 0;';
    echo '    left: 0;';
    echo '    right: 0;';
    echo '    bottom: 0;';
    echo '    background: rgba(0, 0, 0, 0.5); /* Yarı saydam siyah katman */';
    echo '    z-index: -1; /* İçerikten geride */';
    echo '}';
    echo '.navbar {';
    echo '    display: flex;';
    echo '    justify-content: space-between;';
    echo '    align-items: center;';
    echo '    padding: 10px 20px;';
    echo '    background-color: rgba(255, 255, 255, 0.8);';
    echo '    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);';
    echo '}';
    echo '.navbar a.logo-link {';
    echo '    display: flex;';
    echo '    align-items: center;';
    echo '    text-decoration: none;';
    echo '}';
    echo '.navbar img {';
    echo '    width: 200px;';
    echo '    height: auto;';
    echo '    margin-right: 10px;';
    echo '}';
    echo '.navbar ul {';
    echo '    list-style-type: none;';
    echo '    display: flex;';
    echo '}';
    echo '.navbar ul li {';
    echo '    margin-right: 10px;';
    echo '}';
    echo '.navbar ul li a {';
    echo '    text-decoration: none;';
    echo '    padding: 8px 15px;';
    echo '    border-radius: 5px;';
    echo '    color: #fff;';
    echo '}';
    echo '.navbar ul li a.logout-btn {';
    echo '    background-color: #FF0000;';
    echo '}';
    echo '.navbar ul li a.home-btn {';
    echo '    background-color: #FF0000;';
    echo '}';
    echo '.navbar ul li a.settings-btn {';
    echo '    background-color: #FF0000;';
    echo '}';
    echo '.navbar ul li a.profile-btn {';
    echo '    background-color: #FF0000;';
    echo '}';
    echo '.navbar ul li a.reservations-btn {';
    echo '    background-color: #FF0000;';
    echo '}';
    echo '.container {';
    echo '    max-width: 800px;';
    echo '    margin: 50px auto;';
    echo '    background: rgba(255, 255, 255, 0.8);';
    echo '    padding: 20px;';
    echo '    border-radius: 10px;';
    echo '    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);';
    echo '}';
    echo '.academic-container {';
    echo '    display: flex;';
    echo '    justify-content: space-between;';
    echo '    align-items: center;';
    echo '    padding: 10px;';
    echo '    margin-bottom: 10px;';
    echo '    border-bottom: 1px solid #ddd;';
    echo '}';
    echo '.academic-container span {';
    echo '    font-size: 18px;';
    echo '}';
    echo '.academic-container img {';
    echo '    width: 100px;';
    echo '    height: 100px;';
    echo '    border-radius: 50%;';
    echo '    margin-right: 10px;';
    echo '}';
    echo '.academic-container button {';
    echo '    background-color: #4CAF50;';
    echo '    color: white;';
    echo '    padding: 10px 20px;';
    echo '    border: none;';
    echo '    border-radius: 5px;';
    echo '    cursor: pointer;';
    echo '}';
    echo '.academic-container button:hover {';
    echo '    background-color: #45a049;';
    echo '}';
    echo '</style>';
    echo '</head>';
    echo '<body>';

    // Navbar kodu
    echo '<div class="navbar">';
    echo '<a href="home.php" class="logo-link">';
    echo '<img src="neu-logo.png" alt="NEU Logo">';
    echo '</a>';
    echo '<ul>';
    echo '<li><a href="index.php" class="logout-btn">Log Out</a></li>';
    echo '<li><a href="student_pending_reservations.php" class="reservations-btn">Pending Reservations</a></li>';
    echo '<li><a href="student_accepted_reservations.php" class="reservations-btn">Accepted Reservations</a></li>';
    echo '<li><a href="settings.php" class="settings-btn">Settings</a></li>';
    echo '<li><a href="home.php" class="home-btn">Home</a></li>';
    echo '<li><a href="profile.php" class="profile-btn">Profile</a></li>';
    echo '</ul>';
    echo '</div>';

    if ($result_academics->num_rows > 0) {
        echo '<div class="container">';
        echo '<h2>Search Results</h2>';
        while ($row = $result_academics->fetch_assoc()) {
            $academic_id = $row['id'];
            $fullname = $row['title'] . ' ' . $row['firstname'] . ' ' . $row['lastname'];
            $profile_picture = $row['profile_picture'];

            // Timetable tablosundan akademik dönem ve yılı al
            $query_timetable = "SELECT DISTINCT semester, year FROM timetable WHERE academic_id = '$academic_id'";
            $result_timetable = $connection_academics->query($query_timetable);

            if ($result_timetable->num_rows > 0) {
                while ($row_timetable = $result_timetable->fetch_assoc()) {
                    $semester = $row_timetable['semester'];
                    $year = $row_timetable['year'];

                    // Container yapısı içinde akademisyen adı, unvanı, akademik dönem ve yıl ile buton ve profil resmi
                    echo '<div class="academic-container">';
                    if (!empty($profile_picture) && $profile_picture != 'default.jpg') {
                        echo '<img src="../academician_website/uploads/' . $profile_picture . '" alt="Profile Picture">';
                    } else {
                        echo '<img src="default_profile_picture.png" alt="Default Profile Picture">';
                    }
                    echo '<span>' . $fullname . ' - ' . $semester . ' ' . $year . '</span>';
                    echo '<form action="view_timetable.php" method="get" style="display:inline;">';
                    echo '<input type="hidden" name="academic_id" value="' . $academic_id . '">';
                    echo '<button type="submit">Open Timetable</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }
        }
        echo '</div>';
    } else {
        echo '<div class="container">';
        echo '<p>No results found for "' . htmlspecialchars($search_query) . '".</p>';
        echo '</div>';
    }

    echo '</body>';
    echo '</html>';
} else {
    echo '<p>Please use the search form on the home page.</p>';
}
?>
