function validateForm() {
    let name = document.getElementById("fullName").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if (name.length < 3) {
        alert("Please enter a valid full name (minimum 3 letters).");
        return false;
    }
    if (!email.match(emailPattern)) {
        alert("Please enter a valid email address.");
        return false;
    }
    if (phone.length < 11) {
        alert("Please provide a valid 11-digit Bangladeshi contact number.");
        return false;
    }
    return true;
}