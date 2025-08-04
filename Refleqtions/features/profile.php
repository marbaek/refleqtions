<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Start output buffering to prevent headers already sent error
ob_start();

include($_SERVER['DOCUMENT_ROOT'] . "/Refleqtions/include/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/Refleqtions/assets/php/connections.php");

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user profile information
$query = "SELECT * FROM profile_tbl WHERE username = ? LIMIT 1";
$stmt = $crud_connections->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

// Handle profile updates (excluding favorite quote)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $date_of_birth = trim($_POST['date_of_birth']);
    $gender = trim($_POST['gender']);
    $updated_at = date('Y-m-d H:i:s');
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Update profile
    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql_profile = "UPDATE profile_tbl SET full_name = ?, email = ?, phone_number = ?, address = ?, date_of_birth = ?, gender = ?, password = ?, updated_at = ? WHERE username = ?";
        $stmt_profile = $crud_connections->prepare($sql_profile);
        $stmt_profile->bind_param("sssssssss", $full_name, $email, $phone_number, $address, $date_of_birth, $gender, $hashed_password, $updated_at, $username);

        $sql_login = "UPDATE login_tbl1 SET password = ? WHERE username = ?";
        $stmt_login = $login_connections->prepare($sql_login);
        $stmt_login->bind_param("ss", $hashed_password, $username);
    } else {
        $sql_profile = "UPDATE profile_tbl SET full_name = ?, email = ?, phone_number = ?, address = ?, date_of_birth = ?, gender = ?, updated_at = ? WHERE username = ?";
        $stmt_profile = $crud_connections->prepare($sql_profile);
        $stmt_profile->bind_param("ssssssss", $full_name, $email, $phone_number, $address, $date_of_birth, $gender, $updated_at, $username);
    }

    if ($stmt_profile->execute() && (!empty($new_password) ? $stmt_login->execute() : true)) {
        $_SESSION['success_message'] = "Profile updated successfully";
    } else {
        $_SESSION['error_message'] = "Error updating profile: " . $stmt_profile->error;
    }
    header("Location: profile.php");
    exit();
}

// Handle favorite quote update separately
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quote'])) {
    $favorite_quote = trim($_POST['favorite_quote']);
    $updated_at = date('Y-m-d H:i:s');

    $sql_quote = "UPDATE profile_tbl SET favorite_quote = ?, updated_at = ? WHERE username = ?";
    $stmt_quote = $crud_connections->prepare($sql_quote);
    $stmt_quote->bind_param("sss", $favorite_quote, $updated_at, $username);

    if ($stmt_quote->execute()) {
        $_SESSION['success_message'] = "Favorite quote updated successfully";
    } else {
        $_SESSION['error_message'] = "Error updating favorite quote: " . $stmt_quote->error;
    }
    header("Location: profile.php");
    exit();
}

// Handle profile picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
    $target_dir = "../assets/img/profile_pictures/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error_message'] = "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["profile_picture"]["size"] > 5000000) {
        $_SESSION['error_message'] = "File is too large.";
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif', 'jfif'])) {
        $_SESSION['error_message'] = "Invalid file type.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $sql_update_picture = "UPDATE profile_tbl SET profile_picture = ? WHERE username = ?";
            $stmt_update_picture = $crud_connections->prepare($sql_update_picture);
            $stmt_update_picture->bind_param("ss", $target_file, $username);

            if ($stmt_update_picture->execute()) {
                $_SESSION['success_message'] = "Profile picture updated successfully.";
            } else {
                $_SESSION['error_message'] = "Error updating profile picture.";
            }
        } else {
            $_SESSION['error_message'] = "Error uploading file.";
        }
    }
    header("Location: profile.php");
    exit();
}

// Handle delete profile picture
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_picture'])) {
    $sql_delete_picture = "UPDATE profile_tbl SET profile_picture = NULL WHERE username = ?";
    $stmt_delete_picture = $crud_connections->prepare($sql_delete_picture);
    $stmt_delete_picture->bind_param("s", $username);

    if ($stmt_delete_picture->execute()) {
        $_SESSION['success_message'] = "Profile picture deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting profile picture: " . $stmt_delete_picture->error;
    }
    header("Location: profile.php");
    exit();
}

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// End output buffering
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Card - <?php echo htmlspecialchars($username); ?></title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" href="assets/css/style-profile.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    .quote-modal-content {
    position: relative;
    margin: 5% auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    width: 90%;
    max-width: 434px;
    max-height: 70vh; /* Set max height */
    overflow-y: auto; /* Enable vertical scrolling */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .profile-modal-content {
    position: relative;
    margin: 5% auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    width: 90%;
    max-width: 434px;
    max-height: 70vh; /* Set max height */
    overflow-y: auto; /* Enable vertical scrolling */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

      @media (max-width: 768px) {
    .modal-content {
        width: 90%;
        height: 25%; /* Stretch to full viewport height */
        max-height: none; /* Remove any height limit */
    }

    .quote-modal-content {
        width: 90%;
        height: 35%; /* Stretch to full viewport height */
        max-height: none; /* Remove any height limit */
    }

    .profile-modal-content {
        width: 90%;
        height: 95%; /* Stretch to full viewport height */
        max-height: none; /* Remove any height limit */
    }

    .profile-container {
        width: 95%;
        max-width: none;
        height: 95%; /* Stretch to full viewport height */
        max-height: none; /* Remove any height limit */
    }
}
</style>
<body class="min-h-screen w-full overflow-x-hidden bg-gray-50 text-gray-800">
    <div class="profile-container flex flex-col md:flex-row items-center justify-center gap-6 p-6 w-full max-w-6xl mx-auto">
        <!-- LEFT SECTION -->
        <div class="profile-left w-full md:w-1/2 flex flex-col items-center text-center">
            <div class="relative left-2 w-40 h-40">
                <img src="<?php echo isset($user_data['profile_picture']) && $user_data['profile_picture'] ? 'features/' . htmlspecialchars($user_data['profile_picture']) : 'assets/img/profile-picture.jpg'; ?>" 
                    alt="Profile Picture" 
                    id="profile-pic" 
                    class="w-full h-full object-cover rounded-full border-4 border-white shadow-md cursor-pointer">
                
                <!-- Dropdown Menu -->
                <div id="profile-dropdown" class="hidden absolute right-2 mt-2 w-48 bg-blue rounded-md">
                    <form action="features/profile.php" method="POST" enctype="multipart/form-data">
                        <button type="button" id="changeProfilePicBtn" name="change_picture" class="btn-prf w-full mb-3">Change Profile Picture</button>
                        <button type="button" id="deleteProfilePicBtn" class="btn-prf w-full" onclick="showDeletePicModal()">Delete Profile Picture</button>
                    </form>
                </div>
            </div>

            <div class="full-name mb-10">
                <h2 class="text-3xl font-bold" style="width: 300px; height: 50px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                <?php echo htmlspecialchars($user_data['full_name']); ?></h2>
            </div>

            <div class="favorite-quote" style="width: 250px; height: 50px; margin-bottom: 150px; margin-top: -50px;">
                <h3 class="font-semibold">Favorite Quote:</h3>
                <hr>
                <p id="changeQuoteBtn" class="italic" >
                    <?php echo htmlspecialchars($user_data['favorite_quote'] ?? 'No favorite quote set'); ?>
                </p>
            </div>
        </div>

        <!-- RIGHT SECTION -->
        <div class="profile-right w-full">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center md:text-left">👤 Your Profile</h2>

    <div class="space-y-6">
        <!-- Username -->
        <div class="profile-field">
            <label class="flex items-center text-gray-600 font-semibold mb-1">
                <i data-lucide="user" class="w-5 h-5 mr-2"></i> Username:
            </label>
            <p class="text-gray-900 pl-7"><?php echo htmlspecialchars($user_data['username']); ?></p>
            <hr class="mt-2 border-gray-300">
        </div>

        <!-- Email -->
        <div class="profile-field">
            <label class="flex items-center text-gray-600 font-semibold mb-1">
                <i data-lucide="mail" class="w-5 h-5 mr-2"></i> Email:
            </label>
            <p class="text-gray-900 pl-7"><?php echo htmlspecialchars($user_data['email']); ?></p>
            <hr class="mt-2 border-gray-300">
        </div>

        <!-- Phone Number -->
        <div class="profile-field">
            <label class="flex items-center text-gray-600 font-semibold mb-1">
                <i data-lucide="phone" class="w-5 h-5 mr-2"></i> Phone Number:
            </label>
            <p class="text-gray-900 pl-7"><?php echo htmlspecialchars($user_data['phone_number']); ?></p>
            <hr class="mt-2 border-gray-300">
        </div>

        <!-- Address -->
        <div class="profile-field">
            <label class="flex items-center text-gray-600 font-semibold mb-1">
                <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i> Address:
            </label>
            <p class="text-gray-900 pl-7"><?php echo htmlspecialchars($user_data['address']); ?></p>
            <hr class="mt-2 border-gray-300">
        </div>

        <!-- Gender -->
        <div class="profile-field">
            <label class="flex items-center text-gray-600 font-semibold mb-1">
                <i data-lucide="users" class="w-5 h-5 mr-2"></i> Gender:
            </label>
            <p class="text-gray-900 pl-7"><?php echo htmlspecialchars($user_data['gender']); ?></p>
            <hr class="mt-2 border-gray-300">
        </div>

        <!-- Date of Birth -->
        <div class="profile-field">
            <label class="flex items-center text-gray-600 font-semibold mb-1">
                <i data-lucide="calendar" class="w-5 h-5 mr-2"></i> Date of Birth:
            </label>
            <p class="text-gray-900 pl-7"><?php echo htmlspecialchars($user_data['date_of_birth']); ?></p>
            <hr class="mt-2 border-gray-300">
        </div>
    </div>

    <div class="mt-8 text-center md:text-right">
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition duration-200" onclick="showUpdateModal()">
             Update Profile
        </button>
    </div>
</div>

<!-- Include Lucide icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>


    <!-- Modal Structure for Update Profile -->
    <div id="updateModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 overflow-auto">
        <div class="profile-modal-content bg-white p-6 rounded-lg w-full max-w-2xl mx-auto mt-10 md:mt-20">
            <span class="close text-xl font-bold float-right cursor-pointer" onclick="closeModal('updateModal')">&times;</span>
            <h2 class="text-lg font-semibold mb-4">Update Profile Information</h2>
            <form action="features/profile.php" method="POST" id="updateForm">
                <div class="profile-section mb-4">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="modal_full_name" name="full_name" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required class="w-full mt-1">
                </div>
                <div class="profile-section mb-4">
                    <label for="username">Username</label>
                    <input type="text" id="modal_username" value="<?php echo htmlspecialchars($user_data['username']); ?>" readonly class="w-full mt-1">
                </div>
                <div class="profile-section mb-4">
                    <label for="email">Email</label>
                    <input type="email" id="modal_email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required class="w-full mt-1">
                </div>
                <div class="profile-section mb-4">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" 
                        id="modal_phone_number" 
                        name="phone_number" 
                        value="<?php echo htmlspecialchars($user_data['phone_number']); ?>" 
                        maxlength="11" 
                        pattern="\d{11}" 
                        oninput="validatePhoneNumber(this)" 
                        required 
                        title="Phone number must be exactly 11 digits."
                        class="w-full mt-1">
                </div>
                <div class="profile-section mb-4">
                    <label for="address">Address</label>
                    <input type="text" id="modal_address" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" required class="w-full mt-1">
                </div>

                <div class="profile-section two-fields flex flex-col md:flex-row gap-4 mb-4">
                    <div class="w-full">
                        <label for="gender">Gender</label>
                        <select name="gender" id="modal_gender" required class="w-full mt-1">
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo ($user_data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($user_data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Others" <?php echo ($user_data['gender'] == 'Others') ? 'selected' : ''; ?>>Others</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="modal_date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user_data['date_of_birth']); ?>" required class="w-full mt-1">
                    </div>
                </div>

                <div class="profile-section mb-4">
                    <label for="new_password">New Password</label>
                    <input type="password" id="modal_new_password" name="new_password" placeholder="Enter new password" class="w-full mt-1">
                </div>
                <div class="profile-section mb-6">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="modal_confirm_password" name="confirm_password" placeholder="Confirm new password" class="w-full mt-1">
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <button type="submit" class="modal-button bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" name="update_profile">Save Changes</button>
                    <button type="button" class="modal-button bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500" onclick="closeModal('updateModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Confirming Profile Picture Change -->
    <div id="profilePicModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('profilePicModal')">&times;</span>
            <h2>Change Profile Picture</h2>
            <form action="features/profile.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_picture" accept="image/*" required>
                <button type="submit" class="modal-button">Save</button>
                <button type="button" class="modal-button" onclick="closeModal('profilePicModal')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Modal for Confirming Quote Change -->
    <div id="quoteModal" class="modal">
        <div class="quote-modal-content">
            <span class="close" onclick="closeModal('quoteModal')">&times;</span>
            <h2>Change Your Favorite Quote</h2>
            <form method="POST" action="features/profile.php">
                <textarea name="favorite_quote" id="favorite_quote" class="quote-textarea" required><?= htmlspecialchars($user_data['favorite_quote']) ?></textarea>
                <button type="submit" class="modal-button" name="update_quote">Add Quote</button>
                <button type="button" class="modal-button" onclick="closeModal('quoteModal')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Modal for Confirming Profile Picture Deletion -->
    <div id="deletePicModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 overflow-auto">
        <div class="modal-content">
            <span class="close text-xl font-bold float-right cursor-pointer" onclick="closeModal('deletePicModal')">&times;</span>
            <h2 class="text-lg text-center font-semibold mb-4">Delete Profile Picture?</h2>
            <form action="features/profile.php" method="POST">
                <div class="flex justify-end mt-4">
                    <button type="submit" class="modal-button bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" name="delete_picture">Delete</button>
                    <button type="button" class="modal-button bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500" onclick="closeModal('deletePicModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script defer src="assets/script/profile-script.js"></script>
    <script>
        function showDeletePicModal() {
            document.getElementById('deletePicModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>