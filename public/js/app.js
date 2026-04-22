

function toggleDropdown() {
    let menu = document.getElementById("userDropdown");

    if (menu.style.display === "flex") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}

// Close dropdown on outside click
document.addEventListener('click', function(event) {
    let dropdown = document.getElementById("userDropdown");

    if (!event.target.closest('.position-relative')) {
        if (dropdown) dropdown.style.display = "none";
    }
});


function toggleNotification() {
    let box = document.getElementById("notificationBox");

    if (box.style.display === "block") {
        box.style.display = "none";
    } else {
        box.style.display = "block";
    }
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    let notif = document.getElementById("notificationBox");

    if (!e.target.closest('.position-relative')) {
        if (notif) notif.style.display = "none";
    }
});

// Auto hide after 3 sec
window.onload = function () {
    let toast = document.getElementById("toast");
    if (toast) {
        setTimeout(() => {
            toast.style.display = "none";
        }, 3000);
    }
};

function closeToast() {
    document.getElementById("toast").style.display = "none";
}

document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".datepicker", {
        dateFormat: "d/m/Y",
        allowInput: true
    });
});

function togglePassword(element) {
    const input = element.previousElementSibling;
    const icon = element.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

function clearErrors(form) {
    const inputs = form.querySelectorAll(".form-control");
    inputs.forEach(input => input.classList.remove("is-invalid"));

    const errors = form.querySelectorAll(".invalid-feedback");
    errors.forEach(e => e.remove());
}

