// Get the hamburger icon and navigation menu
const hamburger = document.querySelector(".hamburger");
const navLinks = document.querySelector("nav ul");

// Add an event listener for the hamburger icon
hamburger.addEventListener("click", () => {
  // Toggle the "open" class on the navigation menu
  navLinks.classList.toggle("open");
});
