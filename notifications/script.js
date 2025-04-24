const notification_listeners = {
  listeners: [],
  add_listener: function (listener) {
    this.listeners.push(listener);
  },
};

const fetch_notifications = async () => {
  try {
    const res = await fetch("/digifine/notifications/api.php", {
      method: "GET",
    });
    const { success, data } = await res.json();

    if (!success) {
      console.error("Error fetching notifications:", data);
      return;
    }

    notification_listeners.listeners.forEach((listener) => {
      listener(data);
    });
  } catch (error) {
    console.error("Failed to fetch notifications:", error);
  }
};

const delete_notifications = async () => {
  try {
    const res = await fetch("/digifine/notifications/api.php", {
      method: "DELETE",
    });

    const { data, success } = await res.json();

    if (!success) {
      alert("Error deleting notifications: " + data);
      return;
    }

    // Refresh notifications after deletion
    await fetch_notifications();
  } catch (error) {
    console.error("Failed to delete notifications:", error);
  }
};

let notification_interval;

function init_notifications() {
  window.addEventListener("DOMContentLoaded", async () => {
    notification_interval = setInterval(async () => {
      await fetch_notifications();
    }, 30000); // Check every 30 seconds

    // Initial fetch
    await fetch_notifications();
  });

  window.addEventListener("beforeunload", () => {
    clearInterval(notification_interval);
  });
}
