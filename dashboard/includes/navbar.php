<header>
    <div class="logo">
        <img src="/digifine/assets/logo-white.svg" width="70" />
        <span>
            <?= $_SESSION['user']['role'] ?>
        </span>
    </div>
    <nav>
        <button class="sidebar-toggler">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" width="24"
                height="24" stroke="white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <a href="/digifine/dashboard/<?php echo $_SESSION['user']['role']; ?>/notifications/index.php"
            class="notification-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <span class="notification-count" id="notification-count"></span>
        </a>
        <!-- <a href="/digifine/dashboard/<?php echo $_SESSION['user']['role']; ?>/notifications/index.php"
            class="notification-bell">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
            </svg>
            <span class="notification-count" id="notification-count"></span>
        </a> -->

        <a href="/digifine/dashboard/profile">
            <img src="/digifine/assets/user-circle.svg" width="28" />
        </a>
        <!-- <a href="/digifine/logout">Logout</a> -->

    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log({ notification_listeners });

        // Check if notification_listeners exists
        if (typeof notification_listeners !== 'undefined') {
            notification_listeners.add_listener(function (notifications) {
                // Filter unread notifications
                const unreadCount = notifications.filter(item => !item.is_read).length;
                const countElement = document.getElementById('notification-count');

                if (unreadCount > 0) {
                    countElement.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    countElement.style.display = 'block';
                } else {
                    countElement.textContent = '';
                    countElement.style.display = 'none';
                }
            });

            // Make sure notification script is loaded correctly
            console.log("Notification listeners found in navbar");

            // Initialize notifications if not already done
            if (typeof init_notifications === 'function' && typeof notification_interval === 'undefined') {
                init_notifications();
                console.log("Initialized notifications from navbar");
            }
        } else {
            console.error("notification_listeners is not defined. Check if script.js is properly loaded.");

            // Try to load the notification script if it hasn't been loaded
            const scriptElement = document.createElement('script');
            scriptElement.src = '/digifine/notifications/script.js';
            scriptElement.onload = function () {
                console.log("Notification script loaded dynamically");
                if (typeof notification_listeners !== 'undefined') {
                    notification_listeners.add_listener(function (notifications) {
                        const unreadCount = notifications.filter(item => !item.is_read).length;
                        const countElement = document.getElementById('notification-count');

                        if (countElement) {
                            if (unreadCount > 0) {
                                countElement.textContent = unreadCount > 99 ? '99+' : unreadCount;
                                countElement.style.display = 'block';
                            } else {
                                countElement.textContent = '';
                                countElement.style.display = 'none';
                            }
                        }
                    });

                    if (typeof init_notifications === 'function') {
                        init_notifications();
                    }
                }
            };
            document.head.appendChild(scriptElement);
        }
    });
</script>


<style>
    .notification-button {
        position: relative;
        display: inline-block;
        color: #white;
    }

    .notification-count {
        position: absolute;
        top: -5px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        display: none;
        text-align: center;
        line-height: 18px;
    }

    .notification-count.active {
        display: flex;
        /* Only show when has content */
    }
</style>