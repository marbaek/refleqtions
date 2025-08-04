<?php
session_start(); // Start the session

if (isset($_SESSION['username'])) {
  header("Location: features/user.php"); // or loading.php if that's your main page
  exit();
}

$username = $password = "";
$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username
    if (empty($username)) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($username);
    }

    // Validate password
    if (empty($password)) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($password);
    }

    // Only process login if both username and password are valid
    if ($username && $password) {
        include("assets/php/connections.php");

        // Query to check if the username exists (case-sensitive)
        $check_username = mysqli_query($login_connections, "SELECT * FROM login_tbl1 WHERE BINARY username = '$username'");
        $check_username_row = mysqli_num_rows($check_username);

        if ($check_username_row > 0) {
            $row = mysqli_fetch_assoc($check_username);
            $db_password = $row["password"];

            if (!password_verify($password, $db_password)) {
                $passwordErr = "Password is incorrect";
            } else {
                // Login successful, set session and redirect
                $_SESSION['username'] = $username;
                header("Location: loading.php"); // Redirect (prevents form resubmission)
                exit();
            }
        } else {
            $usernameErr = "Username is not registered!";
        }
    }
}

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#99042E">
  <title>Login | RefleQtions</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <link rel="stylesheet" type="text/css" href="assets/css/style-login.css"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#4a90e2">
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js')
      .then(() => console.log('SW registered'));
  }
</script>

</head>
<body class="bg-cover bg-center min-h-screen flex flex-col justify-center items-center px-4">
<img src="assets/img/background.png" alt="Background" class="fixed top-0 left-0 w-full h-full object-cover -z-10">
  
  <!-- Login Box -->
  <div class="bg-gradient-to-br from-gray-390 to-blue-390 rounded-2xl shadow-2xl w-96 max-w-full p-5">
  <div class="flex justify-center mb-4">
      <a href="landing.php">
        <img src="assets/img/logo.png" alt="Logo" class="w-40">
      </a>
    </div>
    
    <form method="post" onsubmit="return checkDataToSend(this);" action="" class="space-y-4 text-center">

<!-- Username with Icon -->
<div class="relative">
  <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
  <input type="text"
         name="username"
         id="username"
         value="username"
         autocomplete="off"
         class="w-full pl-10 p-2.5 text-lg font-sans text-blue-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
         onfocus="removeDefault(this, 'username')"
         onblur="setDefault(this, 'username')" />
</div>

<!-- Password with Icon + Eye Toggle -->
<div class="relative">
  <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
  <input type="password"
         name="password"
         id="password"
         value="Password"
         autocomplete="off"
         class="w-full pl-10 p-2.5 text-lg font-sans text-blue-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
         onfocus="removeDefault(this, 'Password')"
         onblur="setDefault(this, 'Password')"
         oninput="showIcon()" />
  <i class="fas fa-eye eye-icon absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-700 cursor-pointer hidden" id="togglePassword"></i>
</div>

      <input type="submit" name="btn" value="Login"
             class="w-full bg-blue-900 hover:bg-[#0a214f6b] text-white text-lg font-semibold py-2 rounded-md cursor-pointer transition duration-300" />

      <p class="text-sm">Don't have an account? <a href="register.php" class="text-blue-700 hover:underline">Register here</a></p>

      <?php if (isset($_SESSION['success_message'])) {
          echo "<div class='text-green-600 font-bold'>" . $_SESSION['success_message'] . "</div>";
          unset($_SESSION['success_message']);
      } ?>
    </form>

    <p class="text-blue-700 text-sm mt-2">
      <?php echo !empty($usernameErr) ? $usernameErr . "<br>" : ""; ?>
      <?php echo !empty($passwordErr) ? $passwordErr : ""; ?>
    </p>
  </div>

  <!-- Quote Box -->
  <div class="mt-3 w-96 max-w-full bg-gradient-to-br from-gray-390 to-blue-400 text-white rounded-xl shadow-2xl p-6 transform transition-all hover:scale-105 hover:shadow-2xl">
    <p class="text-center text-lg font-sans">
        <span class="font-semibold">Quote of the Day!</span><br><br>
        <span id="quoteText" class="block text-xl mb-2"></span>
        <span id="quoteAuthor" class="float-right italic text-sm"></span>
    </p>
</div>
  <!-- Scripts -->
  <script src="assets/script/dq-script.js"></script>
  <script>
    function removeDefault(element, defaultText) {
      if (element.value === defaultText) {
        element.value = '';
        element.style.color = '#000';
      }
    }

    function setDefault(element, defaultText) {
      if (element.value === '') {
        element.value = defaultText;
        element.style.color = 'rgb(12,36,84)';
      }
    }

    function showIcon() {
      const passwordField = document.getElementById('password');
      const icon = document.getElementById('togglePassword');
      icon.style.display = passwordField.value.length > 0 ? 'inline' : 'none';
    }

    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordField = document.getElementById('password');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        this.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        passwordField.type = 'password';
        this.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  </script>
</body>
</html>

