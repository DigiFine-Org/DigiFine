<div class="alert-container">
    <div class="popup" id="popup">
        <img src="/digifine/assets/alerts/error.png" alt="alert_icon" />
        <h2 class="failedH2">Failed</h2>
        <p><?php echo htmlspecialchars($message ?? "Operation failed!"); ?></p>
        <button class="ok-button" onclick="closePopup()">OK</button>
    </div>
</div>

<!-- Automatically trigger the popup when the page loads -->
<script>
    function openPopup() {
        const popup = document.getElementById("popup");
        const alertContainer = document.querySelector(".alert-container");

        alertContainer.style.display = "flex"; // Make the container visible

        // Adding a small delay to let the container be visible before the popup appears
        setTimeout(() => {
            popup.classList.add("open-popup"); // Corrected to add the class after display is set
        }, 50); // Small delay to ensure the alert container shows first
    }

    function closePopup() {
        const popup = document.getElementById("popup");
        const alertContainer = document.querySelector(".alert-container");

        popup.classList.remove("open-popup"); // Remove the class to start the closing animation

        // After the closing animation, hide the overlay
        setTimeout(() => {
            alertContainer.style.display = "none"; // Hide the overlay after animation
        }, 400); // Duration of the transition (should match the CSS transition time)
    }

    // Automatically show the popup when the page loads (or you can trigger this when needed)
    window.onload = function() {
        openPopup(); // Trigger the popup display
    };
</script>
<!-- <script src="popup.js"></script> -->