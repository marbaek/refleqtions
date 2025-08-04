<?php
session_start();
include("assets/php/connections.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loading...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mobile scaling -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Redirect to the main user page after 2 seconds
        setTimeout(() => {
            window.location.href = 'features/user.php';
        },2000);
    </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-[#1b2a52]">

<div class="flex justify-center pt-72 min-h-screen">
<img src="assets/img/logo.png" alt="Loading..." class="w-[220px] h-[220px] animate-pulse">
</div>


</body>
</html>

