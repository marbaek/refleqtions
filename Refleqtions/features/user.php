<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

include("../assets/php/connections.php");
include("../include/header.php");

$query = "SELECT * FROM login_tbl1 WHERE username = '$username'";
$result = mysqli_query($login_connections, $query);
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style-user.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style_show_ads.css">
    <script defer src="assets/script/user-script.js"></script>
</head>
   <!-- for ads showing-->
    <div class="ads_show" id="ads_show">
                    <div class="ads_counter" id="ads_counter">Ads will be skipped in: </div>
                    <div class="ads_sample" id="ads_sample"></div>
    </div>
    
<body class="bg-cover bg-no-repeat min-h-screen" id="container_body">
    
    <div class="flex flex-col items-center justify-center px-4 py-6 text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6"></h1>

        <!-- Quote Container -->
        <div id="quote-container" class="bg-white bg-opacity-90 rounded-2xl p-6 w-full max-w-2xl shadow-lg">
            <!-- Generating message -->
            <div id="generating-message" class="text-gray-500 mb-4 hidden"></div>

            <!-- Quote -->
            <div class="quote-text text-lg sm:text-xl mb-3 text-gray-800 flex flex-col items-center">
                <i class="fas fa-quote-left text-gray-600 mb-1"></i>
                <span id="quote"></span>
                <i class="fas fa-quote-right text-gray-600 mt-1"></i>
            </div>

            <!-- Author -->
            <div class="quote-author text-gray-600 text-sm mb-4">
                <span id="author"></span>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                <button id="copy-quote"
                    class="w-full sm:w-auto bg-blue-500 text-white text-sm px-3 py-1.5 rounded-md hover:bg-blue-600 transition-all duration-200">
                    Copy
                </button>
                <button id="new-quote"
                    class="w-full sm:w-auto bg-green-500 text-white text-sm px-3 py-1.5 rounded-md hover:bg-green-600 transition-all duration-200">
                    Generate Quote
                </button>
                <button id="add_quote" onclick="add_qoute('<?php echo $user_id;?>')"
                    class="w-full sm:w-auto bg-green-500 text-white text-sm px-3 py-1.5 rounded-md hover:bg-green-600 transition-all duration-200">
                    Add to my favourites
                </button>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader" class="mt-4 hidden"></div>

        <!-- Category Buttons -->
        <div class="category-button-container mt-6 w-full max-w-4xl">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('general')">General</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('love')">Love</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('philosophy')">Philosophy</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('bible')">Bible</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('attitude')">Attitude</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('beauty')">Aesthetics</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('best')">Excellence</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('men')">Men</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('money')">Wealth</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('morning')">Morning</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('motivational')">Motivation</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('music')">Music</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('nature')">Nature</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('patience')">Patience</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('peace')">Peace</button>
                <button class="category-btn bg-gray-200 text-sm px-3 py-1.5 rounded-md" onclick="setCategory('failure')">Failure</button>
            </div>
        </div>
    </div>
</body>

</html>
<script src="savequote/addquotefunction.js" defer></script>