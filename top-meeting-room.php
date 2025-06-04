<?php
require_once 'classes/MeetingRoom.php';

$room = new MeetingRoom();

$office = $_GET['office'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

$topRooms = $room->getTopRooms($office, $startDate, $endDate);

// Jika gagal, kembalikan response kosong
if (!is_array($topRooms)) {
    $response = [
        "labels" => [],
        "values" => []
    ];
} else {
    $labels = array_column($topRooms, 'room_name');
    $values = array_column($topRooms, 'total_booking');

    $response = [
        "labels" => $labels,
        "values" => $values
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
