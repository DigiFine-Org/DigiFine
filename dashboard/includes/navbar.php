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
        

        <a href="/digifine/dashboard/profile">
            <img src="/digifine/assets/user-circle.svg" width="28" />
        </a>

    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log({ notification_listeners });

        if (typeof notification_listeners !== 'undefined') {
            notification_listeners.add_listener(function (notifications) {
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

            console.log("Notification listeners found in navbar");

            if (typeof init_notifications === 'function' && typeof notification_interval === 'undefined') {
                init_notifications();
                console.log("Initialized notifications from navbar");
            }
        } else {
            console.error("notification_listeners is not defined. Check if script.js is properly loaded.");

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
    }
</style>