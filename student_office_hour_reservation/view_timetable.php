<?php
session_start();
require_once('../academician_website/config_db.php');
require_once('config.php');

if (isset($_GET['academic_id'])) {
    $academic_id = $_GET['academic_id'];

    // Akademisyen bilgilerini al
    $query_academic = "SELECT * FROM academics WHERE id = '$academic_id'";
    $result_academic = $connection_academics->query($query_academic);

    if ($result_academic->num_rows > 0) {
        $academic = $result_academic->fetch_assoc();
        $academic_name = $academic['title'] . ' ' . $academic['firstname'] . ' ' . $academic['lastname'];
    } else {
        die("Academic not found.");
    }

    // Timetable verilerini al
    $query_timetable = "SELECT * FROM timetable WHERE academic_id = '$academic_id'";
    $result_timetable = $connection_academics->query($query_timetable);

    if ($result_timetable === false) {
        die("SQL Error: " . $connection_academics->error);
    }

    // Akademik yıl ve dönem verilerini al
    $query_semester = "SELECT DISTINCT semester, year FROM timetable WHERE academic_id = '$academic_id'";
    $result_semester = $connection_academics->query($query_semester);

    if ($result_semester->num_rows > 0) {
        $semester_data = $result_semester->fetch_assoc();
        $academic_semester = $semester_data['semester'];
        $academic_year = $semester_data['year'];
    } else {
        $academic_semester = 'N/A';
        $academic_year = 'N/A';
    }

    // Timetable verilerini tabloya yerleştir
    $timetable = [];
    while ($row = $result_timetable->fetch_assoc()) {
        $day = $row['day_of_week'];
        $time = $row['time_of_day'];
        $activity = $row['activity_type'];
        $lecture = isset($row['lecture_code']) ? $row['lecture_code'] : '';

        $timetable[$day][$time] = $activity . ($lecture ? " ($lecture)" : '');
    }

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Timetable</title>';
    echo '<link rel="stylesheet" type="text/css" href="styles.css">'; // External CSS
    echo '<style>';
    echo 'body {';
    echo '    background-image: url("home_background_img.jpg");';
    echo '    background-size: cover;';
    echo '    font-family: Arial, sans-serif;';
    echo '    color: #333;';
    echo '    display: flex;';
    echo '    flex-direction: column;';
    echo '    align-items: center;';
    echo '    min-height: 100vh;';
    echo '    margin: 0;';
    echo '}';
    echo 'body::after {';
    echo '    content: "";';
    echo '    display: block;';
    echo '    position: fixed;';
    echo '    top: 0;';
    echo '    left: 0;';
    echo '    right: 0;';
    echo '    bottom: 0;';
    echo '    background: rgba(0, 0, 0, 0.5);';
    echo '    z-index: -1;';
    echo '}';
    echo '.container {';
    echo '    max-width: 1000px;';
    echo '    background: rgba(255, 255, 255, 0.9);';
    echo '    padding: 20px;';
    echo '    border-radius: 10px;';
    echo '    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);';
    echo '    margin-top: 20px;';
    echo '}';
    echo 'table {';
    echo '    width: 100%;';
    echo '    border-collapse: collapse;';
    echo '}';
    echo 'th, td {';
    echo '    border: 1px solid #ddd;';
    echo '    padding: 8px;';
    echo '    text-align: center;';
    echo '}';
    echo 'th {';
    echo '    background-color: #ffeeba;';
    echo '    font-weight: bold;';
    echo '}';
    echo '.time-column {';
    echo '    background-color: #ffc0cb;';  // Pembe
    echo '}';
    echo '.day-row {';
    echo '    background-color: #bee5eb;';
    echo '}';
    echo '.soh {';
    echo '    background-color: #98fb98;';  // Açık yeşil
    echo '    cursor: pointer;';
    echo '}';
    echo '.aoh {';
    echo '    background-color: #ffcc00;';  // Canlı sarı
    echo '}';
    echo '.in-lecture {';
    echo '    background-color: #66b3ff;';  // Canlı mavi
    echo '}';
    echo '.navbar {';
    echo '    display: flex;';
    echo '    justify-content: space-between;';
    echo '    align-items: center;';
    echo '    padding: 10px 20px;';
    echo '    background-color: rgba(255, 255, 255, 0.8);';
    echo '    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);';
    echo '    width: 100%;';
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
    echo '    margin: 0;';
    echo '    padding: 0;';
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
    echo '.modal {';
    echo '    display: none;';
    echo '    position: fixed;';
    echo '    z-index: 1;';
    echo '    left: 0;';
    echo '    top: 0;';
    echo '    width: 100%;';
    echo '    height: 100%;';
    echo '    overflow: auto;';
    echo '    background-color: rgba(0, 0, 0, 0.5);';
    echo '    padding-top: 50px;';
    echo '}';
    echo '.modal-content {';
    echo '    background-color: #fefefe;';
    echo '    margin: auto;';
    echo '    padding: 20px;';
    echo '    border: 1px solid #888;';
    echo '    width: 80%;';
    echo '    max-width: 500px;';
    echo '    border-radius: 10px;';
    echo '    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);';
    echo '}';
    echo '.close {';
    echo '    color: #aaa;';
    echo '    float: right;';
    echo '    font-size: 28px;';
    echo '    font-weight: bold;';
    echo '}';
    echo '.close:hover, .close:focus {';
    echo '    color: black;';
    echo '    text-decoration: none;';
    echo '    cursor: pointer;';
    echo '}';
    echo 'label, textarea, select, button {';
    echo '    display: block;';
    echo '    width: 100%;';
    echo '    margin-bottom: 10px;';
    echo '}';
    echo 'textarea {';
    echo '    resize: vertical;';
    echo '}';
    echo 'button {';
    echo '    background-color: #4CAF50;';
    echo '    color: white;';
    echo '    border: none;';
    echo '    padding: 10px 20px;';
    echo '    cursor: pointer;';
    echo '}';
    echo 'button:hover {';
    echo '    background-color: #45a049;';
    echo '}';
    echo '</style>';
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script>';
    echo '$(document).ready(function() {';
    echo '    $(".soh").click(function() {';
    echo '        var academicId = ' . $academic_id . ';';
    echo '        var day = $(this).closest("tr").find("td:first-child").text();';
    echo '        var time = $(this).closest("tr").find("td").eq($(this).index()).text();';
    echo '        $("#academic_id").val(academicId);';
    echo '        $("#reservation_day").val(day);';
    echo '        $("#reservation_time").val(time);';
    echo '        $("#reservationModal").show();';
    echo '    });';
    echo '    $(".close").click(function() {';
    echo '        $("#reservationModal").hide();';
    echo '    });';
    echo '    $("#sendRequest").click(function(e) {';
    echo '        e.preventDefault();';
    echo '        $.ajax({';
    echo '            url: "send_reservation_request.php",';
    echo '            type: "POST",';
    echo '            data: $("#reservationForm").serialize(),';
    echo '            dataType: "json",';
    echo '            success: function(response) {';
    echo '                if (response.success) {';
    echo '                    alert("Reservation request sent successfully!");';
    echo '                    $("#reservationModal").hide();';
    echo '                } else {';
    echo '                    alert("Error: " + response.error);';
    echo '                }';
    echo '            }';
    echo '        });';
    echo '    });';
    echo '});';
    echo '</script>';
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

    echo '<div class="container">';
    if ($result_timetable->num_rows > 0) {
        echo '<h2>Timetable for ' . $academic_name . ' - ' . $academic_semester . ' ' . $academic_year . '</h2>';
        echo '<table>';
        echo '<tr class="day-row">';
        echo '<th>Time/Day</th>';
        echo '<th>Monday</th>';
        echo '<th>Tuesday</th>';
        echo '<th>Wednesday</th>';
        echo '<th>Thursday</th>';
        echo '<th>Friday</th>';
        echo '<th>Saturday</th>';
        echo '</tr>';

        $timeslots = [
            '09:00 - 09:50',
            '10:00 - 10:50',
            '11:00 - 11:50',
            '12:00 - 12:50',
            '13:00 - 13:50',
            '14:00 - 14:50',
            '15:00 - 15:50',
            '16:00 - 16:50',
            '17:00 - 17:50',
            '18:00 - 18:50',
            '19:00 - 19:50'
        ];

        foreach ($timeslots as $timeslot) {
            echo '<tr>';
            echo '<td class="time-column">' . $timeslot . '</td>';
            for ($day = 1; $day <= 6; $day++) {
                $class = '';
                if (isset($timetable[$day][$timeslot])) {
                    $activity = explode(' ', $timetable[$day][$timeslot])[0];
                    if ($activity === 'SOH') {
                        $class = 'soh';
                    } elseif ($activity === 'AOH') {
                        $class = 'aoh';
                    } elseif ($activity === 'In') {
                        $class = 'in-lecture';
                    }
                }
                echo '<td class="' . $class . '">';
                echo isset($timetable[$day][$timeslot]) ? $timetable[$day][$timeslot] : '';
                echo '</td>';
            }
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>No timetable found for the selected academician.</p>';
    }
    echo '</div>';

    // Modal
    echo '<div id="reservationModal" class="modal">';
    echo '<div class="modal-content">';
    echo '<span class="close">&times;</span>';
    echo '<form id="reservationForm">';
    echo '<input type="hidden" name="academic_id" id="academic_id">';
    echo '<input type="hidden" name="reservation_day" id="reservation_day">';
    echo '<input type="hidden" name="reservation_time" id="reservation_time">';
    echo '<label for="reservation_reason">Reason:</label>';
    echo '<select name="reservation_reason" id="reservation_reason" required>';
    echo '<option value="About Course">About Course</option>';
    echo '<option value="Course Registration">Course Registration</option>';
    echo '<option value="About Exam">About Exam</option>';
    echo '<option value="Internship">Internship</option>';
    echo '<option value="Project">Project</option>';
    echo '<option value="Consult">Consult</option>';
    echo '<option value="Other">Other</option>';
    echo '</select>';
    echo '<br>';
    echo '<label for="reservation_details">Details:</label>';
    echo '<textarea name="reservation_details" id="reservation_details" maxlength="100000" required></textarea>';
    echo '<br>';
    echo '<button id="sendRequest">Send Request</button>';
    echo '<button id="closeModal">Cancel</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';

    echo '</body>';
    echo '</html>';
} else {
    echo '<p>Invalid academician ID.</p>';
}
?>
