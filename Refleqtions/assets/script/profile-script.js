
        // Profile Picture Modal Logic
        var profilePicModal = document.getElementById("profilePicModal");
        var changeProfilePicBtn = document.getElementById("changeProfilePicBtn");
        var closeProfilePicModal = profilePicModal.getElementsByClassName("close")[0];

        changeProfilePicBtn.onclick = function() {
            profilePicModal.style.display = "block";
        }

        closeProfilePicModal.onclick = function() {
            closeModal('profilePicModal');
        }

        window.onclick = function(event) {
            if (event.target == profilePicModal) {
                closeModal('profilePicModal');
            }
        }
        
        const profilePic = document.getElementById('profile-pic');
        const dropdown = document.getElementById('profile-dropdown');

        profilePic.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Optional: Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!profilePic.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
        // Quote Modal Logic
        var quoteModal = document.getElementById("quoteModal");
        var changeQuoteBtn = document.getElementById("changeQuoteBtn");
        var closeQuoteModal = quoteModal.getElementsByClassName("close")[0];

        changeQuoteBtn.onclick = function() {
            quoteModal.style.display = "block";
        }

        closeQuoteModal.onclick = function() {
            closeModal('quoteModal');
        }

        window.onclick = function(event) {
            if (event.target == quoteModal) {
                closeModal('quoteModal');
            }
        }

        // Update Profile Modal Logic
        var updateModal = document.getElementById("updateModal");
        var closeUpdateModal = updateModal.getElementsByClassName("close")[0];

        closeUpdateModal.onclick = function() {
            closeModal('updateModal');
        }

        window.onclick = function(event) {
            if (event.target == updateModal) {
                closeModal('updateModal');
            }
        }

        // Show the delete profile picture modal
const deleteProfilePicBtn = document.getElementById('deleteProfilePicBtn');
const deleteProfilePicModal = document.getElementById('deleteProfilePicModal');

deleteProfilePicBtn.addEventListener('click', () => {
    deleteProfilePicModal.classList.remove('hidden');
});

// Confirm deletion and submit the form
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
confirmDeleteBtn.addEventListener('click', () => {
    // Create a hidden form to submit the delete request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'features/profile.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_profile_picture';
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
});


        function showUpdateModal() {
            document.getElementById('updateModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('updateModal')) {
                closeModal('updateModal');
            }
        }

        function validatePhoneNumber(input) {
            // Replace non-numeric characters with an empty string
            input.value = input.value.replace(/\D/g, '');

            // Limit the length to 11 characters
            if (input.value.length > 11) {
                input.value = input.value.slice(0, 11);
            }
        }
