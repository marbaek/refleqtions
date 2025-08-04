<?php
$user_id = $_GET['user_id'];
header('Content-Type: application/json');
include("../assets/php/connections.php");
$reactions = [
    "Happy" =>[],
    "Sad" => [],
    "Angry" => [],
    "Relaxed" => [],
    "Excited" => []
];

$sql = "SELECT mood, description FROM journal_tbl where mood != '' AND user_id = $user_id";
$query = $crud_connections->query($sql);


while($row = $query->fetch_assoc()){
    $mood = $row['mood'];
    $journal = $row['description'];
    $reactions[$mood][] = $journal;
}

echo json_encode($reactions);
?>