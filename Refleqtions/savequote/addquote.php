<?php
date_default_timezone_set('Asia/Manila');
include('../assets/php/connections.php');

header('Content-Type: application/json');
if(isset($_POST['user_id'])){
    echo json_encode(
        [
            "user_id" => $_POST['user_id'],
            "author" => $_POST['author'],
            "quote" => $_POST['quote']
        ]
);
}else{
    echo json_encode(["response" => "Received but not accurate"]);
}

$user_id = $_POST['user_id'];
$author = $_POST['author'];
$quote = $_POST['quote'];
$timestamp = time();

$sql = "INSERT INTO `qoutes_tbl`(`user_id`, `author`, `quote`, `timestamp`) VALUES (?,?,?,?)";
$stmt = $crud_connections->prepare($sql);
$stmt->bind_param("isss", $user_id, $author, $quote, $timestamp);
$stmt->execute();
$stmt->close();
$crud_connections->close();
?>