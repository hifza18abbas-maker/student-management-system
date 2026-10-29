document.addEventListener("DOMContentLoaded", function () {

    // Loading overlay
    var overlay = document.createElement("div");
    overlay.className = "loading-overlay";
    overlay.innerHTML = '<div class="spinner"></div><p>Please wait...</p>';
    document.body.appendChild(overlay);

    function showError(input, message) {
        clearError(input);
        input.classList.add("input-error");
        var msg = document.createElement("small");
        msg.className = "field-error";
        msg.textContent = message;
        input.parentNode.appendChild(msg);
    }

    function clearError(input) {
        input.classList.remove("input-error");
        var old = input.parentNode.querySelector(".field-error");
        if (old) old.remove();
    }

    function validateForm(form) {
        var valid = true;
        var fields = form.querySelectorAll("input, select, textarea");

        fields.forEach(function (f) {
            clearError(f);
            var value = f.value.trim();
            var name = f.name;

            if (f.hasAttribute("required") && value === "") {
                showError(f, "This field is required.");
                valid = false;
                return;
            }
            if (name === "name" && value !== "" && value.length < 2) {
                showError(f, "Name must be at least 2 characters.");
                valid = false;
            }
            if (name === "email" && value !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                showError(f, "Please enter a valid email address.");
                valid = false;
            }
            if (name === "phone" && value !== "" && !/^[0-9+\-\s]{7,20}$/.test(value)) {
                showError(f, "Phone must be 7-20 digits (numbers, +, - only).");
                valid = false;
            }
            if (name === "password" && form.querySelector('[name="confirm_password"]') && value.length < 6) {
                showError(f, "Password must be at least 6 characters.");
                valid = false;
            }
            if (name === "confirm_password") {
                var pass = form.querySelector('[name="password"]').value;
                if (value !== pass) {
                    showError(f, "Passwords do not match.");
                    valid = false;
                }
            }
        });
        return valid;
    }

    var forms = document.querySelectorAll("form[method='POST'], form[method='post']");
    forms.forEach(function (form) {
        if (form.classList.contains("inline-form")) return; // delete buttons

        form.setAttribute("novalidate", "");

        // Clear error while typing
        form.querySelectorAll("input, select, textarea").forEach(function (f) {
            f.addEventListener("input", function () { clearError(f); });
        });

        form.addEventListener("submit", function (e) {
            if (!validateForm(form)) {
                e.preventDefault();
                var first = form.querySelector(".input-error");
                if (first) first.focus();
                return;
            }
            // Valid: show loading
            overlay.classList.add("show");
            var btn = form.querySelector("button[type='submit']");
            if (btn) {
                btn.textContent = "Please wait...";
                setTimeout(function () { btn.disabled = true; }, 0);
            }
        });
    });
});