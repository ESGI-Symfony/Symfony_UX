const eye = document.querySelector(".password-toggle");
const eyeOff = document.querySelector(".password-toggle-off");
const passwordField = document.querySelector("#registration_form_plainPassword, #inputPassword");

eye?.addEventListener("click", () => {
    eye.style.display = "none";
    eyeOff.style.display = "block";
    if (passwordField !== null) {
        passwordField.type = "text";
    }
});

eyeOff?.addEventListener("click", () => {
    eyeOff.style.display = "none";
    eye.style.display = "block";
    if (passwordField !== null) {
        passwordField.type = "password";
    }
});
