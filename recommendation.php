<?php
require_once 'classes/MeetingRoom.php';

header('Content-Type: application/json');

$room = new MeetingRoom();
$recommendations = [];

// Ambil data
$totalMeetings = $room->getTotalMeetings();
$roomStats = $room->getRoomUsageStats();

// Debug: tampilkan nilai mentah
if (!$totalMeetings || empty($roomStats)) {
    echo json_encode([
        "debug" => [
            "totalMeetings" => $totalMeetings,
            "roomStats" => $roomStats
        ],
        "recommendations" => $recommendations
    ]);
    exit;
}

// Proses rekomendasi untuk tingkat okupansi ruang meeting
foreach ($roomStats as $roomData) {
    // Menghitung tingkat okupansi ruang
    $occupancyRate = ($roomData['frequency'] / $totalMeetings) * 100;
    
    // Jika tingkat okupansi ruang lebih dari 80%, beri rekomendasi
    if ($occupancyRate > 40) {
        $recommendations[] = "📌 *Tingkat okupansi ruang '{$roomData['room_name']}' melebihi 80%*. Pertimbangkan untuk menambah ruang meeting baru.";
    }

    // Proses rekomendasi penggunaan dan perawatan fasilitas
    if ($roomData['frequency'] >= 10 || $roomData['total_duration'] >= 300) {
        $recommendations[] = "🛠️ *Ruang '{$roomData['room_name']}' sering digunakan* ({$roomData['frequency']}x, total {$roomData['total_duration']} menit). Jadwalkan perawatan fasilitas.";
    }
}

echo json_encode($recommendations);
?>
