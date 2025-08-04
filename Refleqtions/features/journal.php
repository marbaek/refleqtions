<?php
session_start();

// Include DB connection
include("../assets/php/connections.php");

// Check login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Get user_id
$query = "SELECT user_id FROM profile_tbl WHERE username = ?";
$stmt = $login_connections->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $mood = $_POST['mood'] ?? null;

    if (is_null($mood)) {
        echo "Mood is required.";
        exit();
    }

    // Handle file upload
    $attachmentPath = '';
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB

        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = basename($_FILES['attachment']['name']);
        $fileSize = $_FILES['attachment']['size'];
        $fileType = $_FILES['attachment']['type'];

        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
            // Absolute server path where file will be saved
            $uploadDir = realpath(dirname(__FILE__) . '/../assets/img/') . '/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . $fileName;

            // Web-accessible path for img src
            $attachmentPath = 'https://' . $_SERVER['HTTP_HOST'] . '/Refleqtions/assets/img/' . $fileName;



            if (move_uploaded_file($fileTmpPath, $filePath)) {
                // Upload successful
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file type or size.";
            exit();
        }
    }


    // Add note
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $created_at = date("Y-m-d | h:i A");


        $insertQuery = "INSERT INTO journal_tbl (user_id, title, description, mood, attachment_path, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $login_connections->prepare($insertQuery);
        $stmt->bind_param("isssss", $user_id, $title, $description, $mood, $attachmentPath, $created_at);

        if ($stmt->execute()) {
            header("Location: journal.php");
            exit();
        } else {
            echo "Error adding note: " . $stmt->error;
        }
    }

    // Update note
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $noteId = $_POST['noteId'];
        $created_at = date("Y-m-d | h:i A");


        // Get existing attachment if no new one
        if (empty($attachmentPath)) {
            $fetchQuery = "SELECT attachment_path FROM journal_tbl WHERE journal_id = ? AND user_id = ?";
            $stmt = $login_connections->prepare($fetchQuery);
            $stmt->bind_param("ii", $noteId, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $attachmentPath = $result->fetch_assoc()['attachment_path'];
        }

        $updateQuery = "UPDATE journal_tbl SET title = ?, description = ?, mood = ?, updated_at = ?, attachment_path = ? 
                        WHERE journal_id = ? AND user_id = ?";
        $stmt = $login_connections->prepare($updateQuery);
        $stmt->bind_param("sssssii", $title, $description, $mood, $updated_at, $attachmentPath, $noteId, $user_id);
        $stmt->execute();

        header("Location: journal.php");
        exit();
    }
}

// Delete note
if (isset($_GET['delete'])) {
    $noteId = $_GET['delete'];
    $deleteQuery = "DELETE FROM journal_tbl WHERE journal_id = ? AND user_id = ?";
    $stmt = $login_connections->prepare($deleteQuery);
    $stmt->bind_param("ii", $noteId, $user_id);
    $stmt->execute();

    header("Location: journal.php");
    exit();
}

// Fetch notes
$query = "SELECT journal_id, title, description, mood, created_at, updated_at, attachment_path 
          FROM journal_tbl WHERE user_id = ?";
$stmt = $login_connections->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notes = $result->fetch_all(MYSQLI_ASSOC);

// Include header
include("../include/header.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" type="text/css" href="assets/css/style-journal.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release-pro/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("assets/img/background.png");
            background-repeat: no-repeat;
            background-size: cover;
            /* Ensures it covers the whole screen */
            background-position: center;
            /* Keeps it centered on all screen sizes */
            background-attachment: fixed;
            /* Optional: makes the background stay while scrolling */
            background-color: #f5f5f5;
            /* Fallback in case image doesn't load */
        }

        .view-popup {
            background: rgb(255, 255, 255);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 3;
            width: 100%;
            max-width: 1000px;
            transform: translate(-50%, -50%) scale(0.95);
        }

        footer {
         margin-top: 50px;
         padding: 10px;
         }

        @media (max-width: 768px) {
           .modal-content {
              margin-top: 300px;
              width: 80%;
             }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="popup-box flex items-center justify-center min-h-screen">
        <div class="popup rounded-lg shadow-lg w-full h-full max-w-3xl p-10">
            <div
                class="content w-full h-full sm:rounded-lg sm:max-w-3xl sm:mx-auto sm:my-auto sm:shadow-lg sm:p-10 p-6 bg-white overflow-y-auto">
                <div class="popup-header flex justify-between items-center mb-4">
                    <p class="text-lg font-semibold">Add a New Note</p>
                    <i class="uil uil-times cursor-pointer"></i>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row title">
                        <label>Title</label>
                        <input type="text" name="title" spellcheck="false" required>
                    </div>
                    <div class="row mood">
                        <label>Mood</label>
                        <div class="mood-options">
                            <input type="hidden" name="mood" id="selected-mood" required>
                            <label class="mood-happy" onclick="changeMoodColor(this, 'Happy')">😊 Happy |</label>
                            <label class="mood-sad" onclick="changeMoodColor(this, 'Sad')">😢 Sad |</label>
                            <label class="mood-excited" onclick="changeMoodColor(this, 'Excited')">😆 Excited |</label>
                            <label class="mood-angry" onclick="changeMoodColor(this, 'Angry')">😡 Angry |</label>
                            <label class="mood-relaxed" onclick="changeMoodColor(this, 'Relaxed')">😌 Relaxed</label>
                        </div>
                    </div>

                    <div class="row description">
                        <label>Description</label>
                        <textarea name="description" spellcheck="false" required></textarea>
                    </div>

                    <div class="row attachment">
                        <label>Attach File (Optional)</label>
                        <input type="file" name="attachment">
                    </div>

                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="noteId" value="">
                    <button type="submit">Update Note</button>
                </form>

            </div>
        </div>
    </div>

    <div class="view-popup-box fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        style="max-height:none; display: none;">
        <div class="view-popup">
            <div class="content">
                <div class="view-popup-header flex justify-between items-center mb-4">
                    <p class="text-lg font-semibold">Your Note</p>
                    <i class="uil uil-times cursor-pointer text-gray-600 hover:text-gray-800" id="close-view-icon"></i>
                </div>
                <div class="view-content">
                    <h3 id="view-title" class="text-xl font-bold mb-2"></h3>
                    <p id="view-description" class="text-gray-700 mb-4"></p>
                    <h3 id="view-title" class="text-xl font-bold mb-2"></h3>
                    <div id="view-attachment" class="mb-4"></div>
                    <p id="view-description" class="text-gray-700 mb-4"></p>

                    <div class="view-popup-header flex justify-between items-center mb-4">
                    <span id="view-mood"
                       class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                    </span>
                    <span id="view-created-at" class="block text-gray-500 text-sm">
                   </span>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <li class="add-box">
            <div class="icon"><i class="uil uil-plus"></i></div>
            <p>Add new note</p>
        </li>
        <a href="moodtrack/moodcal.php">
        <li class="add-box">
            <div class="icon"><i class="uil uil-smile"></i></div>
            <p>Track your Mood</p>
        </li>
        </a>
        <a href="savequote/savedquote.php">
        <li class="add-box">
            <div class="icon"><i class="uil uil-feedback"></i></div>
            <p>My Saved Quotes</p>
        </li>
        </a>

        <?php
        // Define the emoji mapping for the mood
        $moodEmojis = [
            "Happy" => "😊",
            "Sad" => "😢",
            "Excited" => "😆",
            "Angry" => "😡",
            "Relaxed" => "😌"
        ];

        foreach ($notes as $note):
            $moodEmoji = isset($moodEmojis[$note['mood']]) ? $moodEmojis[$note['mood']] : '';
            ?>
            <li class="note">
                <div class="details" onclick="viewNote(<?php echo $note['journal_id']; ?>)">
                    <p><?php echo htmlspecialchars($note['title']); ?></p>
                </div>
                <div>
                    <span><?php echo nl2br(htmlspecialchars($note['description'])); ?></span>
                </div>

                <div class="bottom-content">
                    <span>
                        <?php
                        echo !empty($note['updated_at']) ? htmlspecialchars($note['updated_at']) : htmlspecialchars($note['created_at']);
                        ?>
                    </span>
                    <span class="mood"><?php echo $moodEmoji . ' ' . htmlspecialchars($note['mood']); ?></span>
                    <div class="settings">
                        <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                        <ul class="menu">
                            <li onclick="viewNote(<?php echo $note['journal_id']; ?>)">
                                <i class="uil uil-eye"></i>View
                            </li>
                            <li
                                onclick="updateNote(<?php echo $note['journal_id']; ?>, '<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['description']); ?>', '<?php echo addslashes($note['mood']); ?>')">
                                <i class="uil uil-pen"></i>Edit
                            </li>
                            <li onclick="deleteNote(<?php echo $note['journal_id']; ?>)">
                                <i class="uil uil-trash"></i>Delete
                            </li>
                    </div>
                    </ul>
                </div>
                <div id="delete-modal" class="modal">
                    <div class="modal-content">
                        <p>Are you sure you want to delete this note?</p>
                        <div class="modal-buttons">
                            <button id="confirm-delete" class="confirm-button">Yes</button>
                            <button id="cancel-delete" class="cancel-button">No</button>
                        </div>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>
    </div>

    <script>
        const addBox = document.querySelector(".add-box"),
            popupBox = document.querySelector(".popup-box"),
            popupTitle = popupBox.querySelector(".popup-header p"),
            closeIcon = popupBox.querySelector(".popup-header i"),
            titleTag = popupBox.querySelector("input"),
            descTag = popupBox.querySelector("textarea"),
            addBtn = popupBox.querySelector("button"),
            viewPopupBox = document.querySelector(".view-popup-box"),
            viewTitle = viewPopupBox.querySelector("#view-title"),
            viewDescription = viewPopupBox.querySelector("#view-description"),
            viewMood = viewPopupBox.querySelector("#view-mood"),
            viewCreatedAt = viewPopupBox.querySelector("#view-created-at"),
            closeViewIcon = viewPopupBox.querySelector("#close-view-icon");

        let isUpdate = false,
            updateId;

        addBox.addEventListener("click", () => {
            popupTitle.innerText = "Add a new Note";
            addBtn.innerText = "Add Note";
            popupBox.classList.add("show");
            document.querySelector("body").style.overflow = "hidden";
        });

        closeIcon.addEventListener("click", () => {
            isUpdate = false;
            titleTag.value = descTag.value = "";
            document.getElementById('selected-mood').value = ""; // Reset mood
            popupBox.classList.remove("show");
            document.querySelector("body").style.overflow = "auto";
        });

        closeViewIcon.addEventListener("click", () => {
            viewPopupBox.style.display = "none";
            document.querySelector("body").style.overflow = "auto";
        });

        function showMenu(elem) {
            elem.parentElement.classList.add("show");
            document.addEventListener("click", e => {
                if (e.target !== elem) {
                    elem.parentElement.classList.remove("show");
                }
            });
        }

        function deleteNote(noteId) {
            const modal = document.getElementById('delete-modal');
            const confirmButton = document.getElementById('confirm-delete');
            const cancelButton = document.getElementById('cancel-delete');

            // Show the modal
            modal.style.display = 'block';

            // Confirm delete
            confirmButton.onclick = function () {
                window.location.href = `features/journal.php?delete=${noteId}`;
            };

            // Cancel delete
            cancelButton.onclick = function () {
                modal.style.display = 'none';
            };
        }


        function updateNote(noteId, title, description, mood) {
            updateId = noteId;
            isUpdate = true;

            // Open the popup form and set it to 'update' mode
            addBox.click();
            titleTag.value = title;
            descTag.value = description;
            document.getElementById('selected-mood').value = mood; // Set mood

            popupTitle.innerText = "Update Note";
            addBtn.innerText = "Update Note";
            document.querySelector("form input[name='action']").value = "update";
            document.querySelector("form input[name='noteId']").value = noteId;

            // Optionally, if you have an attachment, you can add logic to show the attachment preview here.
        }

function viewNote(noteId) {
            if (!isUpdate) {
                const notes = <?php echo json_encode($notes); ?>;
                const note = notes.find(n => n.journal_id === noteId);

                if (note) {
                    viewTitle.innerText = note.title;
                    viewDescription.innerText = note.description;
                    viewMood.innerText = note.mood;
                    viewCreatedAt.innerText = note.created_at;

                    // Show the popup
                    viewPopupBox.style.display = "block";
                    document.querySelector("body").style.overflow = "hidden";

            // Display attachment if available
            const viewAttachment = document.getElementById("view-attachment");
            viewAttachment.innerHTML = ""; // Clear previous content

            if (note.attachment_path) {
                const ext = note.attachment_path.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    viewAttachment.innerHTML = `
                        <img src="${note.attachment_path}" alt="Attachment" 
                             style="max-width: 90%; max-height: 60vh; object-fit: contain; margin-top: 10px; border-radius: 8px;" />
                    `;
                } else {
                    viewAttachment.innerHTML = `
                        <a href="${note.attachment_path}" target="_blank" class="text-blue-600 underline mt-4 block"></a>
                    `;
                }
            }
        }
    }
}

        function changeMoodColor(element, moodType) {
            // Remove active class from all labels
            document.querySelectorAll('.mood-options label').forEach(label => label.classList.remove('active'));

            // Add active class to the clicked label
            element.classList.add('active');

            // Set mood in hidden input
            document.getElementById('selected-mood').value = moodType;

            // Add color class based on mood (optional)
            switch (moodType) {
                case 'happy':
                    element.classList.add('mood-happy');
                    break;
                case 'sad':
                    element.classList.add('mood-sad');
                    break;
                case 'excited':
                    element.classList.add('mood-excited');
                    break;
                case 'angry':
                    element.classList.add('mood-angry');
                    break;
                case 'relaxed':
                    element.classList.add('mood-relaxed');
                    break;
            }
        }
    </script>

</body>

</html>