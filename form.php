<?php 
require_once 'classes/MeetingRoom.php';
$room = new MeetingRoom();
$roomList = $room->getTopRoomsAll(); // Ambil semua ruangan yang pernah dipakai
?>

<!DOCTYPE html>
<html>
<head>
  <title>Input Meeting Baru</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    form {
      max-width: 400px;
    }
    label {
      font-weight: bold;
    }
    input, select {
      width: 100%;
      padding: 8px;
      margin: 5px 0 15px 0;
    }
    button {
      padding: 10px 20px;
    }
  </style>
</head>
<body>
  <h2>Input Meeting Baru</h2> 
  <form action="insert.php" method="POST">
    
    <label>Username:</label>
    <input type="text" name="user_name" required>

    <label>Room Name:</label>
    <select name="room_name" required>
      <option value="">--Pilih Ruangan--</option>
      <?php foreach ($roomList as $room): ?>
        <option value="<?= htmlspecialchars($room['room_name']) ?>">
          <?= htmlspecialchars($room['room_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Tanggal Meeting:</label>
    <input type="date" name="meeting_date" required>

    <label>Pukul Mulai:</label>
    <input type="time" name="start_time" required>

    <label>Pukul Selesai:</label>
    <input type="time" name="end_time" required>

    <label>Office Location:</label>
    <select name="office_location" required>
      <option value="">--Pilih Lokasi--</option>
      <option value="Bandung">Bandung</option>
      <option value="Jakarta">Jakarta</option>
      <option value="Singapore">Singapore</option>
    </select>

    <button type="submit">Tambah Data</button>
  </form>
</body>
</html>
