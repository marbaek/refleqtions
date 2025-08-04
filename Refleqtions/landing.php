<?php
session_start();
include("http://refleqtions.ct.ws/assets/php/connections.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#99042E" />
  <title>Welcome to RefleQtions</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png" />
  <link rel="stylesheet" type="text/css" href="assets/css/style-landing.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="flex flex-col min-h-screen">

 <img src="assets/img/background.png" alt="Background" class="fixed top-0 left-0 w-full h-full object-cover -z-10">
  <!-- Navigation Bar -->
  <nav class="navbar flex flex-col md:flex-row items-center justify-between px-4 py-3">
    <div class="logo flex items-center gap-2">
      <img src="assets/img/logo.png" alt="Logo" class="w-10 h-10" />
      <h1 class="text-xl font-bold">RefleQtions</h1>
    </div>
    <div class="user flex items-center gap-3 mt-3 md:mt-0">
      <a href="login.php" class="btn login-btn">Login</a><br>
      <span>|</span>
      <a href="register.php" class="btn signup-btn">Sign Up</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <header class="hero flex flex-col items-center text-center px-4">
    <div class="hero-content text-white">
      <h1 class="text-2xl md:text-4xl font-bold mb-4">Discover Yourself Through <span>RefleQtion</span></h1>
      <p class="mb-4">Join us on a journey of self-discovery with personalized quotes and a safe journaling space.</p>
      <a href="login.php" class="btn get-started-btn">Get Started</a>
    </div>

    <!-- Features Section (UNTOUCHED) -->
    <div class="features">
      <div class="feature">
        <i class="fas fa-lock"></i>
        <h3>Secure Journaling</h3>
        <p>Write freely without worrying about your thoughts being shared.</p>
      </div>
      <div class="feature">
        <i class="fas fa-quote-left"></i>
        <h3>Personalized Quotes</h3>
        <p>Get daily quotes tailored to your interests and goals.</p>
      </div>
      <div class="feature">
        <i class="fas fa-pen"></i>
        <h3>Reflective Journaling</h3>
        <p>Reflect on your thoughts and feelings with our guided journaling prompts.</p>
      </div>
      <div class="feature">
        <i class="fas fa-video"></i>
        <h3>Motivideo</h3>
        <p>Watch motivational videos to inspire and keep you focused on your journey.</p>
      </div>
      <div class="feature">
        <i class="fas fa-heartbeat"></i>
        <h3>Mood Tracking</h3>
        <p>Track your mood through your words—no forms, just reflections.</p>
      </div>
    </div>
  </header>

  <!-- Footer Section -->
  <footer class="mt-auto bg-blue/80 text-center py-6 text-sm">
    <div class="footer-content flex flex-col items-center gap-3 px-4">
      <p>&copy; 2024 RefleQtions | All Rights Reserved</p>
      <ul class="footer-links flex flex-wrap justify-center gap-4">
        <li><a href="terms.html" class="hover:underline">Terms of Service</a></li>
        <li><a href="privacy.html" class="hover:underline">Privacy Policy</a></li>
        <li><a href="contact.html" class="hover:underline">Contact Us</a></li>
      </ul>
    </div>
  </footer>

</body>
</html>
