<div class="alert-container">

  <!-- <button type="submit" class="button" onclick="openPopup()">Submit</button> -->

  <div class="popup" id="popup">
    <img src="/digifine/assets/alerts/tick.png" alt="tick_icon" />
    <h2 class="successH2">Success</h2>
    <p><?php echo htmlspecialchars($message ?? "Operation Successful!"); ?></p>
    <button class="ok-button" onclick="closePopup()">OK</button>
  </div>
</div>

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
      <?php if (isset($_SESSION["redirect_after_alert"])): ?>
        window.location.href = "<?php echo $_SESSION['redirect_after_alert']; ?>";
        <?php unset($_SESSION["redirect_after_alert"]); ?>
      <?php endif; ?>
    }, 400); // Duration of the transition (should match the CSS transition time)
  }

  // Automatically show the popup when the page loads (or you can trigger this when needed)
  window.onload = function () {
    openPopup(); // Trigger the popup display
  };
</script>