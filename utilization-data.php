<?php
require_once 'classes/MeetingUtilization.php';

$office = $_GET['office'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

$util = new MeetingUtilization();
$row = $util->getStats($office, $startDate, $endDate); // <-- FIX: tambahkan start & end date

$data = [
  [
    "type" => "Average Duration",
    "duration" => round((int)$row['avg_duration']),
    "progress" => round((int)$row['avg_duration'] / 60 * 100)
  ],
  [
    "type" => "Longest Meeting",
    "duration" => (int)$row['max_duration'],
    "progress" => round((int)$row['max_duration'] / 60 * 100)
  ],
  [
    "type" => "Shortest Meeting",
    "duration" => (int)$row['min_duration'],
    "progress" => round((int)$row['min_duration'] / 60 * 100)
  ]
];

header('Content-Type: application/json');
echo json_encode($data);
