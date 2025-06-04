<?php
require_once 'classes/MeetingRoom.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan input
    $room_name = trim($_POST['room_name']);
    $user_name = trim($_POST['user_name']);
    $office_location = isset($_POST['office_location']) ? trim($_POST['office_location']) : '';

    $meeting_date = $_POST['meeting_date']; // format: YYYY-MM-DD
    $start_time = $_POST['start_time'];     // format: HH:MM
    $end_time = $_POST['end_time'];         // format: HH:MM

    // Gabungkan menjadi datetime format
    $start_datetime = $meeting_date . ' ' . $start_time;
    $end_datetime = $meeting_date . ' ' . $end_time;

    // Hitung durasi dalam menit
    $start = new DateTime($start_datetime);
    $end = new DateTime($end_datetime);
    $duration = $start->diff($end)->i + ($start->diff($end)->h * 60);

    // Cek apakah end_time > start_time
    if ($end <= $start) {
        echo "Error: Waktu selesai harus lebih besar dari waktu mulai.";
        exit();
    }

    // Insert ke database
    $room = new MeetingRoom();
    $result = $room->insertWithDatetime($room_name, $start_datetime, $end_datetime, $duration, $user_name, $office_location);

    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error inserting data.";
    }
}
?>

