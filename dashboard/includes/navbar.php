<header>
    <div class="logo">
        <img src="/digifine/assets/logo-white.svg" width="70" />
        <span>
            <?php echo array_key_exists('is_oic', $_SESSION['user']) && $_SESSION['user']['is_oic'] == 1 ? 'oic' : $_SESSION['user']['role'] ?>
        </span>
    </div>
    <nav>
        <button class="sidebar-toggler">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" width="24"
                height="24" stroke="white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <a href="#">
            <img src="/digifine/assets/notification-icon.svg" width="26" />
        </a>
        <a href="/digifine/dashboard/profile">
            <img src="/digifine/assets/user-circle.svg" width="28" />
        </a>
        <!-- <a href="/digifine/logout">Logout</a> -->

    </nav>
</header>