<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$usernameErr = $passwordErr = $confirm_passwordErr = "";

// Include database connection files
include($_SERVER['DOCUMENT_ROOT'] . '/Refleqtions/assets/php/connections.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate username
    if (empty($username)) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($username);
    }

    // Validate password
    if (empty($password)) {
        $passwordErr = "Password is required";
    } elseif (strlen($password) < 8) {
        $passwordErr = "Must have at least 8 characters";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $passwordErr = "Must include at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $passwordErr = "Must include at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $passwordErr = "Must include at least one number";
    } elseif (!preg_match('/[\W_]/', $password)) {
        $passwordErr = "Must include at least one special character";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $confirm_passwordErr = "Please confirm your password";
    } elseif ($password != $confirm_password) {
        $confirm_passwordErr = "Passwords do not match";
    }

    // If there are no validation errors, proceed with inserting the data into the database
    if (empty($usernameErr) && empty($passwordErr) && empty($confirm_passwordErr)) {
        // Check if the username already exists in login_tbl1
        $stmt = $login_connections->prepare("SELECT * FROM login_tbl1 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usernameErr = "Username is already taken!";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare insert statement to insert the new user into login_tbl1
            $stmt = $login_connections->prepare("INSERT INTO login_tbl1 (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            // Execute the insert query for login_tbl1
            if ($stmt->execute()) {
                // Get the last inserted user_id from login_tbl1
                $user_id = $login_connections->insert_id;

                // Prepare insert statement to insert the new user into profile_tbl in cruddb
                $stmt_profile = $crud_connections->prepare("INSERT INTO profile_tbl (user_id, username) VALUES (?, ?)");
                $stmt_profile->bind_param("is", $user_id, $username);

                // Execute the insert query for profile_tbl
                if ($stmt_profile->execute()) {
                    // Prepare insert statement to insert the new user into journal_tbl in cruddb
                    $stmt_journal = $crud_connections->prepare("INSERT INTO journal_tbl (user_id, username, title, description) VALUES (?, ?, 'Welcome!', 'Welcome to your Journal')");
                    $stmt_journal->bind_param("is", $user_id, $username);

                    // Execute the insert query for journal_tbl
                    if ($stmt_journal->execute()) {
                        // Set a session variable for the success message
                        $_SESSION['success_message'] = "Account Registered Successfully!";
                        // Redirect to login page after successful registration
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "Error inserting into journal_tbl: " . $stmt_journal->error;
                    }

                    $stmt_journal->close(); // Close the prepared statement for journal_tbl
                } else {
                    echo "Error inserting into profile_tbl: " . $stmt_profile->error;
                }

                $stmt_profile->close(); // Close the prepared statement for profile_tbl
            } else {
                echo "Error inserting into login_tbl1: " . $stmt->error;
            }
        }

        $stmt->close(); // Close the prepared statement for login_tbl1
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
  <title>Register | RefleQtions</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-cover bg-center min-h-screen flex flex-col justify-center items-center px-4">
<img src="assets/img/background.png" alt="Background" class="fixed top-0 left-0 w-full h-full object-cover -z-10">

  <!-- Registration Box -->
  <div class="bg-gradient-to-br from-gray-390 to-blue-390 rounded-2xl shadow-2xl w-96 max-w-full p-5">
    <div class="flex justify-center mb-4">
      <a href="landing.php">
        <img src="assets/img/logo.png" alt="Logo" class="w-20">
      </a>
    </div>

    <form action="register.php" method="post" class="space-y-4 text-center">
      <!-- Username Field -->
<div class="relative">
  <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
  <input type="text"
         name="username"
         id="username"
         value="<?php echo $username; ?>"
         autocomplete="off"
         placeholder="Enter your username"
         class="w-full pl-10 p-2.5 text-lg font-sans text-blue-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
</div>
<span class="text-red-500 text-sm"><?php echo $usernameErr; ?></span>

<!-- Password Field -->
<div class="relative mb-2">
  <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
  <input type="password"
         name="password"
         id="password"
         placeholder="Enter your password"
         required
         class="w-full pl-10 p-2.5 text-lg font-sans text-blue-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
         oninput="showIcon()" />
  <i class="fas fa-eye eye-icon absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-700 cursor-pointer hidden" id="togglePassword"></i>
</div>

<!-- Password Reminder -->
<div id="passwordReminder" class="text-sm text-gray-700 bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
  <p class="font-semibold text-blue-800 mb-1">Password must contain:</p>
  <ul class="list-disc list-inside text-blue-700">
    <li>At least 8 characters</li>
    <li>At least one uppercase letter (A-Z)</li>
    <li>At least one lowercase letter (a-z)</li>
    <li>At least one number (0-9)</li>
    <li>At least one special character (!@#$%^&*, etc.)</li>
  </ul>
</div>



<span class="text-red-500 text-sm"><?php echo $passwordErr; ?></span>

<!-- Confirm Password Field -->
<div class="relative">
  <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
  <input type="password"
         name="confirm_password"
         id="confirm_password"
         placeholder="Confirm your password"
         required
         class="w-full pl-10 p-2.5 text-lg font-sans text-blue-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
         oninput="showIcon()" />
  <i class="fas fa-eye eye-icon absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-700 cursor-pointer hidden" id="toggleConfirmPassword"></i>
</div>
<span class="text-red-500 text-sm"><?php echo $confirm_passwordErr; ?></span>

      <input type="submit" name="btn" value="Register"
             class="w-full bg-blue-900 hover:bg-[#0a214f6b] text-white text-lg font-semibold py-2 rounded-md cursor-pointer transition duration-300" />

      <p class="text-sm">Already have an account? <a href="login.php" class="text-blue-700 hover:underline">Login here</a></p>

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
  <script src="assets/script/dq-script.js"></script>

  <script>
    function showIcon() {
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        if (passwordField.value) {
            togglePassword.classList.remove('hidden');
        } else {
            togglePassword.classList.add('hidden');
        }

        if (confirmPasswordField.value) {
            toggleConfirmPassword.classList.remove('hidden');
        } else {
            toggleConfirmPassword.classList.add('hidden');
        }
    }

    const passwordField = document.getElementById('password');
  const reminder = document.getElementById('passwordReminder');

  passwordField.addEventListener('focus', () => {
    reminder.style.display = 'block';
  });

  passwordField.addEventListener('blur', () => {
    reminder.style.display = 'none';
  });

  // Optional: hide reminder on page load
  window.onload = () => {
    reminder.style.display = 'none';
  };

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
    });

    // Toggle confirm password visibility
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmPasswordField = document.getElementById('confirm_password');
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
    });
  </script>
</body>

</html>


