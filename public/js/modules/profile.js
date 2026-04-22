document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("profileForm");

    if (form) {
        form.addEventListener("submit", function (e) {

            let isValid = true;

            const firstName = form.querySelector("[name='first_name']");
            const lastName = form.querySelector("[name='last_name']");

            // First Name
            if (firstName.value.trim() === "") {
                showError(firstName, "First name is required");
                isValid = false;
            }

            // Last Name
            if (lastName.value.trim() === "") {
                showError(lastName, "Last name is required");
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }

        });
    }


    function showError(input, message) {
        input.classList.add("is-invalid");

        let error = input.nextElementSibling;
        if (!error || !error.classList.contains("invalid-feedback")) {
            error = document.createElement("div");
            error.className = "invalid-feedback";
            input.parentNode.appendChild(error);
        }

        error.innerText = message;
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    document.addEventListener("input", function (e) {
        if (e.target.classList.contains("is-invalid")) {
            e.target.classList.remove("is-invalid");
        }
    });

});


document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("changePasswordForm");

    if (form) {
        form.addEventListener("submit", function (e) {

            let isValid = true;

            const oldPass = form.querySelector("[name='old_password']");
            const newPass = form.querySelector("[name='new_password']");
            const confirmPass = form.querySelector("[name='new_password_confirmation']");

            // Clear previous errors
            clearErrors(form);

            // Old Password
            if (oldPass.value.trim() === "") {
                showError(oldPass, "Old password is required");
                isValid = false;
            }

            // New Password
            if (newPass.value.trim() === "") {
                showError(newPass, "New password is required");
                isValid = false;
            } else if (newPass.value.length < 6) {
                showError(newPass, "Password must be at least 6 characters");
                isValid = false;
            }

            // Confirm Password
            if (confirmPass.value.trim() === "") {
                showError(confirmPass, "Confirm password is required");
                isValid = false;
            } else if (newPass.value !== confirmPass.value) {
                showError(confirmPass, "Passwords do not match");
                isValid = false;
            }

            // STOP SUBMIT
            if (!isValid) {
                e.preventDefault();
            }

        });
    }

});
