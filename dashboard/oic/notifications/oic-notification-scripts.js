const container = document.getElementById("notifications-container");

notification_listeners.add_listener((items) => {
  container.innerHTML = ""; // Clear container

  if (items.length === 0) {
    container.innerHTML = `
                        <div class="no-notifications">
                                <p>You have no notifications at this time.</p>
                        </div>
                `;
    return;
  }

  // Sort items by date (newest first)
  items.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

  items.forEach((item) => {
    const notification = document.createElement("div");
    notification.classList.add("notification");

    if (item.is_read) {
      notification.classList.add("read");
    }

    // Determine the appropriate href based on notification source
    let href = `/digifine/dashboard/officer/notifications/view.php?id=${item.id}&type=${item.type}`;

    // Handle fine payments or other specific notification types
    if (item.source === "payment_system") {
      // Extract fine ID from the message if it contains a pattern like [FINE_ID:123]
      const match = item.message.match(/\[FINE_ID:(\d+)\]/);
      if (match && match[1]) {
        const fineId = match[1];
        href = `/digifine/dashboard/officer/view-fine/index.php?fine_id=${fineId}`;

        // Remove the [FINE_ID:123] from the displayed message
        item.message = item.message.replace(/\[FINE_ID:\d+\]/, "").trim();
      }
    }

    notification.innerHTML = `
                <a href="${href}">
                        <h3>${item.title}</h3>
                        <p class="message">${
                          item.message.length > 100
                            ? item.message.substring(0, 100) + "..."
                            : item.message
                        }</p>
                        <div class="meta">
                                <span class="date">${new Date(
                                  item.created_at
                                ).toLocaleString()}</span>
                                <span class="source">From: ${item.source}</span>
                        </div>
                </a>
        `;

    container.appendChild(notification);
  });

  // Add clear notifications button if there are personal notifications
  const personalNotifications = items.filter(
    (item) => item.type === "notification"
  );
  if (personalNotifications.length > 0) {
    const clearButton = document.createElement("button");
    clearButton.classList.add("btn");
    clearButton.innerText = "Clear All Notifications";

    clearButton.addEventListener("click", async () => {
      const hasUnread = personalNotifications.some((item) => !item.is_read);

      if (hasUnread) {
        const confirm = window.confirm(
          "Are you sure you want to clear all notifications? There are unread notifications."
        );
        if (!confirm) return;
      }

      await delete_notifications();
    });

    container.appendChild(clearButton);
  }
});

// Initialize notifications
init_notifications();
