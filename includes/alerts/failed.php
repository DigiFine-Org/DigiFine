<div class="alert-container">
    <div class="popup" id="popup">
        <img src="/digifine/assets/alerts/error.png" alt="alert_icon" />
        <h2 class="failedH2">Failed</h2>
        <p><?php echo htmlspecialchars($message ?? "Operation failed!"); ?></p>
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
        }, 400);
    }

    window.onload = function() {
        openPopup();
    };
</script>