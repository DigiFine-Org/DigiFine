function showAlert(message, type = "success") {
  const alertContainer = document.getElementById("alert-container");

  // Set the alert content and styling
  alertContainer.innerHTML = `
        <div class="alert alert-${type}">
            ${message}
        </div>
    `;

  // Show the alert
  alertContainer.style.display = "block";

  // Auto-hide the alert after 3 seconds
  setTimeout(() => {
    alertContainer.style.display = "none";
  }, 3000);
}

function hideAlert() {
  const alertContainer = document.getElementById("alert-container");
  alertContainer.style.display = "none";
}
