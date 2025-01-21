<header>
    <div class="logo">
        <img src="/digifine/assets/logo-white.svg" width="70" />
        <span>
            <?= $_SESSION['user']['role'] ?>
        </span>
    </div>
    <nav>
        <button class="">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" width="24"
                height="24" stroke="black">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <a href="#" class="notification-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <span>1</span>
        </a>
        <a href="/digifine/dashboard/profile">
            <img src="/digifine/assets/user-circle.svg" width="28" />
        </a>
        <!-- <a href="/digifine/logout">Logout</a> -->

    </nav>
</header>