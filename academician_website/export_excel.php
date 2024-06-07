<?php
require 'vendor/autoload.php';
require_once('create_db_connection.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$academic_id = $_POST['academic_id'];

// Fetch timetable data from the database
$query = "SELECT * FROM timetable WHERE academic_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $academic_id);
$stmt->execute();
$result = $stmt->get_result();

$timetable_data = [];

while ($row = $result->fetch_assoc()) {
    $timetable_data[] = $row;
}

$stmt->close();
$conn->close();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Timetable');

$sheet->setCellValue('A1', 'Time');
$sheet->setCellValue('B1', 'Monday');
$sheet->setCellValue('C1', 'Tuesday');
$sheet->setCellValue('D1', 'Wednesday');
$sheet->setCellValue('E1', 'Thursday');
$sheet->setCellValue('F1', 'Friday');
$sheet->setCellValue('G1', 'Saturday');

$row_num = 2;
foreach ($timetable_data as $data) {
    $time = $data['time_of_day'];
    $day = $data['day_of_week'];
    $activity = $data['activity_type'] . ($data['lecture_code'] ? ': ' . $data['lecture_code'] : '');

    switch ($day) {
        case 1:
            $sheet->setCellValue('A' . $row_num, $time);
            $sheet->setCellValue('B' . $row_num, $activity);
            break;
        case 2:
            $sheet->setCellValue('A' . $row_num, $time);
            $sheet->setCellValue('C' . $row_num, $activity);
            break;
        case 3:
            $sheet->setCellValue('A' . $row_num, $time);
            $sheet->setCellValue('D' . $row_num, $activity);
            break;
        case 4:
            $sheet->setCellValue('A' . $row_num, $time);
            $sheet->setCellValue('E' . $row_num, $activity);
            break;
        case 5:
            $sheet->setCellValue('A' . $row_num, $time);
            $sheet->setCellValue('F' . $row_num, $activity);
            break;
        case 6:
            $sheet->setCellValue('A' . $row_num, $time);
            $sheet->setCellValue('G' . $row_num, $activity);
            break;
    }

    $row_num++;
}

$writer = new Xlsx($spreadsheet);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="timetable.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
