const username = prompt("Bitte geben Sie Ihren Benutzernamen ein:");
const password = prompt("Bitte geben Sie Ihr Passwort ein:");

if (username && password) {
    fetch("validate.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "admin.php";
            } else {
                alert("Ungültige Anmeldedaten!");
                window.location.href = "index.php";
            }
        })
        .catch(() => {
            alert("Fehler bei der Anmeldung!");
            window.location.href = "index.php";
        });
} else {
    window.location.href = "/";
}
