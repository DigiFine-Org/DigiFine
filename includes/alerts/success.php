<div class="alert-container">
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

    alertContainer.style.display = "flex";

    setTimeout(() => {
      popup.classList.add("open-popup");
    }, 50);
  }

  function closePopup() {
    const popup = document.getElementById("popup");
    const alertContainer = document.querySelector(".alert-container");

    popup.classList.remove("open-popup");

    setTimeout(() => {
      alertContainer.style.display = "none";
      <?php if (isset($_SESSION["redirect_after_alert"])): ?>
        window.location.href = "<?php echo $_SESSION['redirect_after_alert']; ?>";
        <?php unset($_SESSION["redirect_after_alert"]); ?>
      <?php endif; ?>
    }, 400);
  }

  window.onload = function() {
    openPopup();
  };
</script>