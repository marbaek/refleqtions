<?php
$user_id = $_GET['user_id'];
header('Content-Type: application/json');
include("../assets/php/connections.php");
$sql = "SELECT 
            mood, 
            COUNT(*) AS mood_count,
            ROUND((COUNT(*) * 100.0) / total.total_count, 2) AS percentage
        FROM 
            journal_tbl,
            (SELECT COUNT(*) AS total_count FROM journal_tbl WHERE mood != '' AND user_id = $user_id) AS total
        WHERE 
            mood != '' AND user_id = $user_id
        GROUP BY 
            mood, total.total_count
        ORDER BY percentage DESC;";

$result = $crud_connections->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>