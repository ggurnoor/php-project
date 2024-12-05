// JavaScript for Data Validation and Sanitization

// Function to validate numericality of IDs
function validateNumericId(id) {
    const numericId = parseInt(id, 10);
    if (isNaN(numericId) || numericId <= 0) {
        throw new Error("Invalid ID: IDs must be positive integers.");
    }
    return numericId;
}

// Function to sanitize and escape strings to prevent HTML injection
function sanitizeString(input) {
    const div = document.createElement("div");
    div.appendChild(document.createTextNode(input));
    return div.innerHTML;
}

// Function to validate required fields
function validateRequiredFields(form) {
    const inputs = form.querySelectorAll("input, textarea");
    let isValid = true;

    inputs.forEach((input) => {
        if (input.hasAttribute("required") && input.value.trim() === "") {
            isValid = false;
            input.style.border = "2px solid red"; // Highlight invalid fields
            alert("Please fill out all required fields.");
        } else {
            input.style.border = ""; // Reset border for valid fields
        }
    });

    return isValid;
}

// Function to attach validation to form submissions
function attachFormValidation() {
    const forms = document.querySelectorAll(".form-container");
    forms.forEach((form) => {
        form.addEventListener("submit", (event) => {
            try {
                // Validate required fields
                if (!validateRequiredFields(form)) {
                    event.preventDefault();
                    return;
                }

                // Validate and sanitize inputs
                const inputs = form.querySelectorAll("input[name], textarea[name]");
                inputs.forEach((input) => {
                    const name = input.getAttribute("name");
                    const value = input.value.trim();

                    // Example: Validate numerical ID fields
                    if (name.includes("id")) {
                        input.value = validateNumericId(value);
                    }

                    // Sanitize all string inputs
                    input.value = sanitizeString(value);
                });

                alert("Form submitted successfully!");

            } catch (error) {
                alert(error.message);
                event.preventDefault();
            }
        });
    });
}

// Attach validation on page load
document.addEventListener("DOMContentLoaded", attachFormValidation);
