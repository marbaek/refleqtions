<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
include('../assets/php/connections.php');

$user_id = $_POST['user_id'];

$sql = "SELECT * FROM `qoutes_tbl` 
        WHERE user_id = ?
        ORDER BY RAND()";

$stmt = $crud_connections->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$results = $result->fetch_all(MYSQLI_ASSOC);

if ($results) {
    echo json_encode($results);
} else {
    echo json_encode(["response" => "No quotes found for this user."]);
}
?>