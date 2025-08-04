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
            if (confirm("Are you sure you want to delete this note?")) {
                window.location.href = `features/journal.php?delete=${noteId}`;
            }
        }

        function updateNote(noteId, title, description, mood) {
            updateId = noteId;
            isUpdate = true;
            addBox.click();
            titleTag.value = title;
            descTag.value = description;
            document.getElementById('selected-mood').value = mood; // Set mood

            popupTitle.innerText = "Update Note";
            addBtn.innerText = "Update Note";
            document.querySelector("form input[name='action']").value = "update";
            document.querySelector("form input[name='noteId']").value = noteId;
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

                    viewPopupBox.style.display = "block";
                    document.querySelector("body").style.overflow = "hidden";
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