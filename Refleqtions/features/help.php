<?php
session_start();
include("../assets/php/connections.php");
include("../include/header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" type="text/css" href="assets/css/style-help.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release-pro/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <main>
        <div class="container">
            <h2>Frequently Asked Questions (FAQ)</h2>
            <div class="faq">
                <div class="faq-item">
                    <div class="faq-question">1. What is RefleQtions?</div>
                    <div class="faq-answer">
                        RefleQtions is a personalized quote generator and journaling platform designed to inspire and support self-reflection.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">2. How do I save my journal entries?</div>
                    <div class="faq-answer">
                        You can save your journal entries by clicking the 'Save' button in the journal modal. Ensure all fields are filled out correctly.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">3. How do I customize my experience?</div>
                    <div class="faq-answer">
                        You can tailor your experience by selecting your mood and interests when using the journaling and quote features. This ensures personalized content.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">4. How do I change my password?</div>
                    <div class="faq-answer">
                        Log in to your account, navigate to the "Profile" page, and select the "Update Profile" option. Enter your current password and the new password to update it.
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer style="margin-top: 50px;">
        &copy; <?php echo date('Y'); ?> RefleQtions. All Rights Reserved.
    </footer>
</body>

</html>