function openPopup() {
  const popup = document.getElementById("popup");
  const alertContainer = document.querySelector(".alert-container");

  alertContainer.style.display = "flex"; // Show the overlay and popup
  popup.classList.add("open-popup"); // Add the class to trigger the animation
}

function closePopup() {
  const popup = document.getElementById("popup");
  const alertContainer = document.querySelector(".alert-container");

  popup.classList.remove("open-popup"); // Remove the animation class
  setTimeout(() => {
    alertContainer.style.display = "none"; // Hide the overlay and popup after animation
  }, 400); // Wait for the transition duration before hiding
}

// Automatically show the popup when the page loads
window.onload = function () {
  openPopup(); // Show the popup on page load
};
