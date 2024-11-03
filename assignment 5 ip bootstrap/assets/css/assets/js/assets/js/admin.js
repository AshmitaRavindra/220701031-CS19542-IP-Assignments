// admin.js

// Example function to validate blog post forms
function validatePostForm() {
    const titleInput = document.querySelector('#postTitle');
    const contentInput = document.querySelector('#postContent');

    if (titleInput.value.trim() === '') {
        alert('Title cannot be empty!');
        return false;
    }

    if (contentInput.value.trim() === '') {
        alert('Content cannot be empty!');
        return false;
    }

    return true;
}

// Example function to handle form submission
document.addEventListener('DOMContentLoaded', function () {
    const postForm = document.querySelector('#postForm');
    if (postForm) {
        postForm.addEventListener('submit', function (event) {
            if (!validatePostForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
    }
});
