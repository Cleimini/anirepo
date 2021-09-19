<header class="position-fixed p-2 w-100">
    <div class="mx-auto row">
        <div class="col">
            <img alt="AniRepo Logo" class="bg-transparent" draggable="false" src="images/icons/613bfb01b0abf_20210911084033.png">
        </div>

        <div class="col">
            <div class="float-end Hamburger mt-2" id="Show_Navigation_Menu">
                <div class="bg-white"></div>
                <div class="bg-white" id="Center_Burger"></div>
                <div class="bg-white"></div>
            </div>
        </div>
    </div>
</header>

<aside class="overflow-auto position-fixed vh-100">
    <div class="p-3 text-center">
        <a href="index.php"><img alt="No Game No Life: Shiro" class="bg-transparent" draggable="false" src="images/icons/613c1ae029695_20210911105632.png"></a>
    </div>

    <hr class="bg-white mx-3 my-0">

    <nav class="p-3">
        <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" href="index.php" id="Dashboard_Link">
            <span><i class="fas fa-tachometer"></i></span>
            <span>Dashboard</span>
        </a>

        <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_My_Account">
            <span><i class="fas fa-user-cog"></i></span>
            <span>My Account</span>
        </a>

        <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" href="anime.php" id="Anime_Link">
            <span><i class="fas fa-dragon"></i></span>
            <span>Anime</span>
        </a>

        <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" href="favorites.php" id="Favorites_Link">
            <span><i class="fas fa-star"></i></span>
            <span>Favorites</span>
        </a>

        <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" href="reviews.php" id="Reviews_Link">
            <span><i class="fas fa-book-open"></i></span>
            <span>Reviews</span>
        </a>

        <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" href="logs.php" id="Logs_Link">
            <span><i class="fas fa-scroll"></i></span>
            <span>Logs</span>
        </a>

        <?php
            if ($_SESSION["User_ID"] < 2) {
                ?>

                <a class="d-flex flex-row mb-2 rounded text-decoration-none text-white" href="settings.php" id="Settings_Link">
                    <span><i class="fas fa-cogs"></i></span>
                    <span>Settings</span>
                </a>

                <?php
            }
        ?>

        <a class="d-flex flex-row rounded text-decoration-none text-white" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Sign_Out">
            <span><i class="fas fa-sign-out-alt"></i></span>
            <span>Sign Out</span>
        </a>
    </nav>
</aside>