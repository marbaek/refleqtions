<?php
// Establish a connection to the MySQL database
// Establish a connection to the MySQL database
$login_connections = mysqli_connect("sql100.infinityfree.com", "if0_38727199", "VsFSJXlEbI", "if0_38727199_logindb");
$crud_connections = mysqli_connect("sql100.infinityfree.com", "if0_38727199", "VsFSJXlEbI", "if0_38727199_logindb");

// Check the connection
if (!$login_connections || !$crud_connections) {
    die("Connection failed: " . mysqli_connect_error());

}

// Optionally, you can uncomment the following line for debugging
// echo "Connected successfully"; // This line can be removed in production
?>