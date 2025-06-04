<?php
require_once 'db.php';

class MeetingRoom extends Database {

    // Menyisipkan data meeting
    public function insert($room_name, $duration, $user_name, $office_location = null) {
        $conn = $this->connect();
        if ($office_location) {
            $query = "INSERT INTO meetings (room_name, duration, user_name, office_location) VALUES ($1, $2, $3, $4)";
            $params = [$room_name, $duration, $user_name, $office_location];
        } else {
            $query = "INSERT INTO meetings (room_name, duration, user_name) VALUES ($1, $2, $3)";
            $params = [$room_name, $duration, $user_name];
        }
        return pg_query_params($conn, $query, $params);
    }

    // Insert dengan tanggal (tidak lagi digunakan jika sudah ada start_time / end_time)
    public function insertWithDate($room_name, $duration, $meeting_date) {
        $conn = $this->connect();
        $sql = "INSERT INTO meetings (room_name, duration, meeting_date) VALUES ($1, $2, $3)";
        return pg_query_params($conn, $sql, [$room_name, $duration, $meeting_date]);
    }

    // Top rooms
    public function getTopRooms($office = null, $startDate = null, $endDate = null) {
        $conn = $this->connect();
    
        if (!empty($office) && strtolower($office) !== 'semua') {
            if (!empty($startDate) && !empty($endDate)) {
                $query = "SELECT room_name, COUNT(*) AS total_booking 
                          FROM meetings 
                          WHERE office_location = $1 
                            AND start_time BETWEEN $2 AND $3 
                          GROUP BY room_name 
                          ORDER BY total_booking DESC";
                return pg_fetch_all(pg_query_params($conn, $query, [$office, $startDate . " 00:00:00", $endDate . " 23:59:59"]));
            }
    
            $query = "SELECT room_name, COUNT(*) AS total_booking 
                      FROM meetings 
                      WHERE office_location = $1 
                      GROUP BY room_name 
                      ORDER BY total_booking DESC";
            return pg_fetch_all(pg_query_params($conn, $query, [$office]));
        }
    
        $query = "SELECT room_name, COUNT(*) AS total_booking FROM meetings GROUP BY room_name ORDER BY total_booking DESC";
        return pg_fetch_all(pg_query($conn, $query));
    }
    

    // Top users
    public function getTopUsers($office = null, $startDate = null, $endDate = null) {
        $conn = $this->connect();
        $conditions = ["user_name IS NOT NULL", "user_name != ''"];
        $params = [];
    
        if (!empty($office) && strtolower($office) !== 'semua') {
            $conditions[] = "office_location = $" . (count($params) + 1);
            $params[] = $office;
        }
    
        if (!empty($startDate) && !empty($endDate)) {
            $conditions[] = "start_time::date BETWEEN $" . (count($params) + 1) . " AND $" . (count($params) + 2);
            $params[] = $startDate;
            $params[] = $endDate;
        }
    
        $whereClause = "WHERE " . implode(" AND ", $conditions);
        $query = "
            SELECT user_name, COUNT(*) AS total_bookings, SUM(duration) AS total_duration
            FROM meetings
            $whereClause
            GROUP BY user_name
            ORDER BY total_bookings DESC
            LIMIT 5
        ";
    
        $result = pg_query_params($conn, $query, $params);
        return pg_fetch_all($result);
    }
    

    // Statistik umum ruang
    public function getRoomUsageStats() {
        $conn = $this->connect();
        $query = "SELECT room_name, COUNT(*) as frequency, SUM(duration) as total_duration FROM meetings GROUP BY room_name ORDER BY frequency DESC";
        return pg_fetch_all(pg_query($conn, $query));
    }

    // Statistik berdasarkan tanggal
    public function getRoomUsageByDate($startDate, $endDate) {
        $conn = $this->connect();
        $query = "SELECT room_name, COUNT(*) as frequency, SUM(duration) as total_duration FROM meetings WHERE start_time BETWEEN $1 AND $2 GROUP BY room_name ORDER BY frequency DESC";
        return pg_fetch_all(pg_query_params($conn, $query, [$startDate . " 00:00:00", $endDate . " 23:59:59"]));
    }

    // Total semua meeting
    public function getTotalMeetings() {
        $conn = $this->connect();
        $result = pg_query($conn, "SELECT COUNT(*) as total FROM meetings");
        return pg_fetch_result($result, 0, 'total');
    }

    // Statistik ringkasan
    public function getSummaryStats($office = null, $startDate = null, $endDate = null) {
        $conn = $this->connect();
        $conditions = [];
        $params = [];
        $i = 1;
    
        // Office filter
        if (!empty($office) && strtolower($office) !== 'semua') {
            $conditions[] = "office_location = $" . $i++;
            $params[] = $office;
        }
    
        // Date filter
        if (!empty($startDate) && !empty($endDate)) {
            $conditions[] = "start_time BETWEEN $" . $i++ . " AND $" . $i++;
            $params[] = $startDate . " 00:00:00";
            $params[] = $endDate . " 23:59:59";
        }
    
        $whereClause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";
    
        // Query 1: total meetings and total duration
        $query1 = "SELECT COUNT(*) AS total_meetings, SUM(duration) AS total_minutes FROM meetings $whereClause";
        $data1 = pg_fetch_assoc(pg_query_params($conn, $query1, $params));
    
        // Query 2: count unique rooms
        $query2 = "SELECT COUNT(DISTINCT room_name) AS room_count FROM meetings $whereClause";
        $roomCount = (int) pg_fetch_result(pg_query_params($conn, $query2, $params), 0, 0);
    
        // Estimate unbooked time capacity
        $interval = (!empty($startDate) && !empty($endDate)) 
            ? (new DateTime($startDate))->diff(new DateTime($endDate))->days + 1
            : 1; // fallback to 1 day
    
        $totalCapacityMinutes = $roomCount * $interval * 10 * 60;
        $usedMinutes = (int) ($data1['total_minutes'] ?? 0);
        $unbookedMinutes = max(0, $totalCapacityMinutes - $usedMinutes);
    
        // Query 3: peak hour calculation
        $peakQuery = "SELECT EXTRACT(HOUR FROM start_time) AS hour, COUNT(*) AS total 
                      FROM meetings $whereClause 
                      GROUP BY hour 
                      ORDER BY total DESC 
                      LIMIT 1";
        $peakResult = pg_query_params($conn, $peakQuery, $params);
        $peakHour = pg_fetch_assoc($peakResult)['hour'] ?? null;
    
        return [
            'bookings' => (int) ($data1['total_meetings'] ?? 0),
            'hours_booked' => round($usedMinutes / 60, 1),
            'unbooked_capacity' => $totalCapacityMinutes > 0
                ? round(($unbookedMinutes / $totalCapacityMinutes) * 100, 1)
                : 0,
            'peak_hour' => $peakHour !== null ? $peakHour . ":00" : "-"
        ];
    }
    
}
?>

