<?php
// Check if a session is already started, if not, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Fetch user profile information to get the profile picture
include("../assets/php/connections.php");
$query = "SELECT profile_picture FROM profile_tbl WHERE username = ? LIMIT 1";
$stmt = $crud_connections->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$profile_picture = null;
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $profile_picture = isset($user_data['profile_picture']) ? 'features/' . htmlspecialchars($user_data['profile_picture']) : null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?> | RefleQtions</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" href="../assets/css/style-header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <base href="https://refleqtions.ct.ws/Refleqtions/">
    <script defer src="assets/script/user-script.js"></script>
    <img src="assets/img/background.png" alt="Background" class="fixed top-0 left-0 w-full h-full object-cover -z-10">
</head>

<body>
    <header class="flex justify-between items-center px-4 py-3">
        <!-- Logo and Title -->
        <div class="flex items-center gap-2">
            <a href="features/user.php">
              <img src="assets/img/logo.png" alt="Logo" class="w-10 h-10">
            </a>
                <span class="text-lg font-semibold hidden sm:block">RefleQtions</span>
        </div>

        <!-- Nav Menu -->
        <ul class="nav-menu flex gap-2 items-center">
            <li>
                <a href="features/user.php" class="flex items-center gap-1">
                    <i class="fas fa-quote-left"></i>
                    <span class="hidden sm:inline">Quotes</span>
                    <i class="fas fa-quote-right sm:inline"></i>
                </a>
            </li>
            <span>|</span>
            <li>
                <a href="features/journal.php" class="flex items-center gap-1">
                    <i class="fas fa-book"></i>
                    <span class="hidden sm:inline">My Journal</span>
                </a>
            </li>
            <span>|</span>
            <li>
                <a href="features/video.php" class="flex items-center gap-1">
                    <i class="fas fa-video"></i>
                    <span class="hidden sm:inline">Motivideo</span>
                </a>
            </li>
            <span>|</span>
            <li>
                <a href="features/qbot.php" class="flex items-center gap-1">
                    <i class="fas fa-robot"></i>
                    <span class="hidden sm:inline">QBot</span>
                </a>
            </li>
        </ul>

        <!-- User Info -->
        <div class="user-info flex items-center gap-3 relative">
            <div class="user-avatar-container relative">
                <div class="user-avatar"
                    style="background-image: url('<?php echo isset($profile_picture) && $profile_picture ? $profile_picture : 'assets/img/profile-picture.jpg'; ?>');"
                    onclick="toggleDropdown()">
                </div>
                <div class="dropdown-menu absolute right-3 w-40 bg-[#1b2a52] border rounded-md shadow-lg hidden z-50"
                    id="dropdown-menu">
                    <ul>
                        <li><a href="features/profile.php"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="features/help.php"><i class="fas fa-question-circle"></i> Help</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
                    </ul>
                </div>
            </div>
            <div class="user-name hidden sm:block">Hello, <?php echo htmlspecialchars($username); ?>!</div>
        </div>
    </header>

    <script>
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const dropdownMenu = document.getElementById('dropdown-menu');
            const avatar = document.querySelector('.user-avatar');

            if (!avatar.contains(event.target) && dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            }
        });
    </script>

</body>

</html>
