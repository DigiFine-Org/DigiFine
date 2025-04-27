const container = document.getElementById("notifications-container");

notification_listeners.add_listener((items) => {
  container.innerHTML = ""; 

  if (items.length === 0) {
    container.innerHTML = `
            <div class="no-notifications">
                <p>You have no notifications at this time.</p>
            </div>
        `;
    return;
  }

  items.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

  items.forEach((item) => {
    const notification = document.createElement("div");
    notification.classList.add("notification");

    if (item.is_read) {
      notification.classList.add("read");
    }


    let href = `/digifine/dashboard/admin/notifications/view.php?id=${item.id}&type=${item.type}`;


    if (item.source === "fine_system") {
      const match = item.message.match(/\[FINE_ID:(\d+)\]/);
      if (match && match[1]) {
        const fineId = match[1];
        href = `/digifine/dashboard/admin/fines/view-fine.php?fine_id=${fineId}`;

        
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

 
});
