<?php
session_start();
require_once('create_db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['academic_id'])) {
    header("Location: index.php");
    exit;
}

$academic_id = $_SESSION['academic_id'];

// Fetch timetable data from the database
$query = "SELECT * FROM timetable WHERE academic_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $academic_id);
$stmt->execute();
$result = $stmt->get_result();

$timetable_data = [];

while ($row = $result->fetch_assoc()) {
    $time = $row['time_of_day'];
    $day = $row['day_of_week'];
    if (!isset($timetable_data[$time])) {
        $timetable_data[$time] = [];
    }
    $timetable_data[$time][$day] = [
        'activityType' => $row['activity_type'],
        'lectureCode' => $row['lecture_code']
    ];
}

$stmt->close();
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Timetable</title>
    <link rel="stylesheet" type="text/css" href="timetable.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php" class="logo-link">
            <img src="neu-logo.png" alt="NEU Logo">
        </a>
        <ul>
            <li><a href="logout.php" class="logout-btn">Log Out</a></li>
            <li><a href="reservations.php" class="reservations-btn">Accepted Reservations</a></li>
            <li><a href="pending_reservations.php" class="pending-reservations-btn">Pending Reservations</a></li>           
            <li><a href="timetable.php" class="timetable-btn">Timetable</a></li>
            <li><a href="profile.php" class="profile-btn">Profile</a></li>
            <li><a href="settings.php" class="settings-btn">Settings</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="academic-options">
            <label for="academicYear">Academic Year:</label>
            <select id="academicYear">
                <?php 
                $currentYear = date("Y"); 
                for ($year = $currentYear; $year <= 2030; $year++) {
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>
            <label for="academicSemester">Academic Semester:</label>
            <select id="academicSemester">
                <option value="Fall">Fall Term</option>
                <option value="Spring">Spring Term</option>
                <option value="Summer">Summer Term</option>
            </select>
        </div>
        <div class="timetable-container">
            <table id="timetable">
                <tr>
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                </tr>
            </table>
            <div class="button-container">
                <button onclick="saveInformation()">Save All Informations</button>
                <button onclick="resetTable()">Reset All Informations</button>
                <form action="export_excel.php" method="post" style="display:inline;">
                    <input type="hidden" name="academic_id" value="<?php echo $academic_id; ?>">
                    <button type="submit">Export to Excel</button>
                </form>
            </div>
        </div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p>Choose an option:</p>
                <select id="activityType">
                    <option value="SOH">Student Office Hour - SOH</option>
                    <option value="In Lecture">In Lecture</option>
                    <option value="AOH">Academic Office Hour - AOH</option>
                    <option value="Not Available">Not Available</option>
                </select>
                <input type="text" id="lectureCode" placeholder="Enter Lecture Code" style="display:none;">
                <button onclick="addActivity()">Add</button>
            </div>
        </div>
    </div>
    <input type="hidden" id="academicId" value="<?php echo $academic_id; ?>">
    <script>
        const timetableData = <?php echo json_encode($timetable_data); ?>;
    </script>
    <script src="timetable.js"></script>
</body>
</html>
