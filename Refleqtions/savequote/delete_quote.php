<?php
include('../assets/php/connections.php');
header('Content-Type: application/json');

$quote_id = $_GET['quote_id'];

$sql = "DELETE FROM `qoutes_tbl` WHERE quote_id = ?";

$stmt = $crud_connections->prepare($sql);
$stmt->bind_param("i", $quote_id);
$result = $stmt->execute();

echo json_encode(["Response" => "deleted"]);
?>