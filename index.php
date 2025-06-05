<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Meeting Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/chart.min.js"></script> <!-- Chart.js harus di-load lebih dulu -->
  <script>
    // Kirim nilai filter lokasi ke JS
    const office = "<?= $_GET['office'] ?? '' ?>";
    const startDate = "<?= $_GET['start_date'] ?? '' ?>";
    const endDate = "<?= $_GET['end_date'] ?? '' ?>";
  </script>
  <style>
    .filter-container {
      display: flex;
      justify-content: space-between;
      align-items: center;


      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 10px;
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }
  </style>
</head>

<body>

  <div class="container-wrapper">

    <div class="sidebar">
      <div class="logo">
        <img src="logo.png" alt="InMeeT Logo">
        <h2>InMeeT</h2>
        <p>Meeting Room Dashboard</p>
      </div>
      <div class="user-info">
        <p><strong>King Nasarudin</strong></p>
        <small>Super Admin</small>
      </div>
      <ul class="menu">
        <li class="<?= ($_SERVER['REQUEST_URI'] == '/') ? 'active' : '' ?>">
          <a href="/dashboard">📊 Dashboard</a>
        </li>
        <li class="<?= ($_SERVER['REQUEST_URI'] == '/master_data') ? 'active' : '' ?>">
          <a href="/master_data">📁 Master Data</a>
        </li>
        <li class="<?= ($_SERVER['REQUEST_URI'] == '/booking_room') ? 'active' : '' ?>">
          <a href="/booking_room">🗓️ Booking Room</a>
        </li>
        <li class="<?= ($_SERVER['REQUEST_URI'] == '/riwayat_booking') ? 'active' : '' ?>">
          <a href="/riwayat_booking">📜 History</a>
        </li>
      </ul>

      <div class="bottom-menu">
        <a href="#" class="toggle-sidebar">⬅ Hide Sidebar</a>
        <a href="#" class="logout">🔓 Log out</a>
      </div>
    </div>


    <div class="content">
      <h1 class="dashboard-title">Dashboard Meeting Room Usage</h1>

      <form id="filterForm" method="GET" class="filter-container">
        <!-- Filter tanggal di kiri -->
        <div class="filter-group">
          <label for="start_date">Start Date:</label>
          <input type="date" name="start_date" id="startDate" value="<?= $_GET['start_date'] ?? '' ?>">

          <label for="end_date">End Date:</label>
          <input type="date" name="end_date" id="endDate" value="<?= $_GET['end_date'] ?? '' ?>">
        </div>

        <!-- Filter lokasi di kanan -->
        <div class="filter-group">
          <label for="office">Filter by Office:</label>
          <select name="office" id="officeFilter">
            <option value="">Semua Lokasi</option>
            <option value="Bandung" <?= ($_GET['office'] ?? '') === 'Bandung' ? 'selected' : '' ?>>Bandung</option>
            <option value="Jakarta" <?= ($_GET['office'] ?? '') === 'Jakarta' ? 'selected' : '' ?>>Jakarta</option>
            <option value="Singapore" <?= ($_GET['office'] ?? '') === 'Singapore' ? 'selected' : '' ?>>Singapore</option>
          </select>
          <button type="button" id="filterButton">Terapkan</button>
        </div>
      </form>

      <div class="dashboard">

        <div id="booking-data" class="booking-cards">
          <div class="card" id="booking-count">
            <h4>Bookings</h4>
            <p class="value" id="totalBookings">-</p>
            <p class="change">-</p>
          </div>

          <div class="card" id="hours-booked">
            <h4>Hours Booked</h4>
            <p class="value" id="hoursBooked">-</p>
            <p class="change">-</p>
          </div>

          <div class="card" id="unbooked-capacity">
            <h4>Unbooked Capacity</h4>
            <p class="value" id="unbookedCapacity">-</p>
            <p class="change">-</p>
          </div>

          <div class="card" id="peak-hour">
            <h4>Peak Hour</h4>
            <p class="value" id="peakHour">-</p>
            <p class="change">-</p>
          </div>
        </div>

        <!-- Top Meeting Room -->
        <div class="card">
          <h2>Top Meeting Room</h2>
          <div class="chart-container">
            <canvas id="topRoomChart"></canvas>
          </div>
          <div class="refresh-time">Last refresh: <?= date('d/m/Y | H:i'); ?></div>
        </div>

        <!-- Meeting Room Utilization -->
        <div class="card">
          <h2>Meeting Room Utilization</h2>
          <div id="utilizationContainer"></div>
          <div class="refresh-time">Last refresh: <?= date('d/m/Y | H:i'); ?></div>
        </div>

        <!-- Top Users -->
        <div class="card">
          <h2>Top Users</h2>
          <div class="chart-container">
            <canvas id="topUsersChart"></canvas>
          </div>
          <div class="refresh-time">Last refresh: <?= date('d/m/Y | H:i'); ?></div>
        </div>

        <!-- Sistem Rekomendasi -->
        <div class="card">
          <h2>Rekomendasi Sistem</h2>
          <ul id="recommendationList"></ul>
        </div>

      </div>

      <div style="margin-top: 30px; text-align: center;">
        <a href="form.php" style="font-weight: bold; color: #007bff;">+ Tambah Meeting Baru</a>
      </div>
    </div>
  </div>

  <!-- Load script JS -->
  <script src="js/top-meeting-room.js"></script>
  <script src="js/top-users.js"></script>
  <script src="js/utilization.js"></script>
  <script src="js/recommendation.js"></script>
  <script src="js/summary.js"></script>

</body>

</html>