<?php

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

include 'create_db_connection.php';

$year = $data['year'];
$semester = $data['semester'];
$timetable = $data['timetable'];
$academicId = $data['academicId'];

$conn->begin_transaction();

try {
    // Delete existing entries for the academicId, year, and semester
    $delete_query = "DELETE FROM timetable WHERE academic_id = ? AND year = ? AND semester = ?";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bind_param("iis", $academicId, $year, $semester);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Insert new entries
    $smtm = $conn->prepare("INSERT INTO timetable (academic_id, time_of_day, day_of_week, activity_type, lecture_code, year, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($timetable as $timeOfDay => $days) {
        foreach ($days as $dayOfWeek => $activity) {
            $activityType = $activity['activityType'];
            $lectureCode = $activity['lectureCode'] ?? null;
            $smtm->bind_param("issssss", $academicId, $timeOfDay, $dayOfWeek, $activityType, $lectureCode, $year, $semester);

            if (!$smtm->execute()) {
                throw new Exception($smtm->error);
            }
        }
    }

    $smtm->close();
    $conn->commit();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
