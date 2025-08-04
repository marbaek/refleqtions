<?php
session_start();

// Prevent back button from accessing cached pages
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

session_unset();
session_destroy();
include("assets/php/connections.php"); // Optional
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging out...</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Redirect to the login page after 3 seconds
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 3000);
    </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-[#1b2a52]">

<div class="flex justify-center pt-72 min-h-screen">
<img src="assets/img/logo.png" alt="Loading..." class="w-[220px] h-[220px] animate-pulse">
</div>


</body>
</html>
