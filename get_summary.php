<?php
header('Content-Type: application/json');

require_once 'classes/MeetingRoom.php';

$meetingRoom = new MeetingRoom();

$office = $_GET['office'] ?? null;
$start = $_GET['start_date'] ?? null;
$end = $_GET['end_date'] ?? null;

$data = $meetingRoom->getSummaryStats($office, $start, $end);

echo json_encode($data);
?>
