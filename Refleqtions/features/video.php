<?php
session_start();
include("../assets/php/connections.php");
include("../include/header.php");

// Correct path to your JSON file
$videoFile = "../json/videos.json";

if (!file_exists($videoFile)) {
    die("Error: JSON file not found!");
}

// Load JSON data
$videos = json_decode(file_get_contents($videoFile), true);

if (!$videos) {
    die("Error: Failed to decode JSON!");
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Rest of your HTML structure -->


<head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style-video.css">
</head>
<style>
    body {
        background-image: url("assets/img/background.png");
        background-size: cover;
        background-repeat: no-repeat;
    }
</style>

<body>
    <div class="container">
        <div class="video-grid">
            <?php foreach ($videos as $video) : ?>
                <div class="video-container" onclick="openModal('<?php echo $video['id']; ?>')">
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/<?php echo $video['id']; ?>" title="<?php echo htmlspecialchars($video['title']); ?>" allowfullscreen></iframe>
                    </div>
                    <div class="description-container">
                        <h2><?php echo htmlspecialchars($video['title']); ?></h2>
                        <p><?php echo htmlspecialchars($video['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">X</button>
            <iframe id="modalIframe" src="" allowfullscreen></iframe>
        </div>
    </div>

    <script>
        function openModal(videoId) {
            var modal = document.getElementById('videoModal');
            var iframe = document.getElementById('modalIframe');
            iframe.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
            modal.style.display = "flex";
        }

        function closeModal() {
            var modal = document.getElementById('videoModal');
            var iframe = document.getElementById('modalIframe');
            iframe.src = "";
            modal.style.display = "none";
        }
    </script>
</body>

</html>
