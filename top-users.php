<?php
require_once 'classes/MeetingRoom.php';

$room = new MeetingRoom();

// Ambil parameter lokasi dari URL (contoh: ?office=Bandung)
$office = $_GET['office'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Ambil data top users berdasarkan lokasi
$topUsers = $room->getTopUsers($office, $startDate, $endDate);

// Pastikan ada data
$labels = [];
$data = [];

if (!empty($topUsers)) {
    $labels = array_column($topUsers, 'user_name');
    $data = array_column($topUsers, 'total_bookings');
}

// Response untuk chart.js
$response = [
    "labels" => $labels,
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>

