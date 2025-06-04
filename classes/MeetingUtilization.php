<?php
require_once 'db.php';

class MeetingUtilization extends Database {
public function getStats($office = null, $startDate = null, $endDate = null) {
    $conn = $this->connect();
    $params = [];
    $conditions = [];

    if (!empty($office)) {
        $conditions[] = "office_location = $" . (count($params) + 1);
        $params[] = $office;
    }

    if (!empty($startDate) && !empty($endDate)) {
        $conditions[] = "start_time::date BETWEEN $" . (count($params) + 1) . " AND $" . (count($params) + 2);
        $params[] = $startDate;
        $params[] = $endDate;
    }

    $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

    $query = "SELECT AVG(duration) AS avg_duration, 
                     MAX(duration) AS max_duration, 
                     MIN(duration) AS min_duration 
              FROM meetings 
              $where";

    $result = pg_query_params($conn, $query, $params);
    return pg_fetch_assoc($result);
}

}

