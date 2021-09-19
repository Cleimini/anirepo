<main class="float-start position-relative text-white">
    <section>
        <article>
            <h1 class="text-center text-sm-start">
                <strong>
                    <span class="Colored"><span class="Larger-Character">A</span>NI</span><span class="Larger-Character">R</span>EPO
                </strong>
            </h1>

            <h6 class="my-3 text-center text-sm-start">MY ANIME REPOSITORY</h6>

            <ul class="ps-3">
                <li>List down all anime that you've finished watching, anime that you are currently watching, anime that you postponed watching, and anime that plan to watch.</li>
                <li>List down all of your favorite anime, anime characters, and voice actors/actresses.</li>
                <li>Be updated with the trends about anime such as seasons, airing, latest episodes, and all-time populars.</li>
                <li>All datas are being scraped from the website: <a href="https://myanimelist.net/" target="_BLANK"><b>MyAnimeList.net</b></a>.</li>
            </ul>
        </article>

        <article class="mt-5">
            <h3 class="mb-3 text-center text-sm-start"><strong>TOP ANIME THIS SEASON ON MAL</strong></h3>

            <?php
                try {
                    $Select_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Seasonal'");
                    $Select_Trendings->execute();
                    $Trendings = $Select_Trendings->fetchAll();

                    if ($Select_Trendings->rowCount() < 1) {
                        echo $Error_Box .= "<h4><strong>NO TRENDING ANIME THIS SEASON</h4></strong> <p>Refresh the page.</p></div>";
                    } else {
                        ?>

                        <div class="owl-carousel owl-theme">
                            <?php
                                foreach ($Trendings as $Trending) {
                                    ?>

                                    <a class="d-block item text-decoration-none text-white" href="<?= $Trending['Trending_MAL_URL']; ?>" target="_BLANK">
                                        <img alt="<?= $Trending['Trending_Title']; ?> Thumbnail" draggable="false" src="<?= $Trending['Trending_Thumbnail']; ?>">

                                        <p class="p-1 text-center"><?= $Trending["Trending_Title"]; ?></p>
                                    </a>
                                    
                                    <?php
                                }
                            ?>
                        </div>

                        <?php
                    }
                } catch(PDOException $Select_Trendings) {
                    echo $Error_Box .= "<h4><strong>SELECT_TRENDINGS</h4></strong> <p>" . $Select_Trendings->getMessage() . ". Refresh the page.</p></div>";
                }
            ?>
        </article>
    </section>
</main>

<aside class="bg-white position-fixed">
    <div class="position-fixed text-center text-white Slider" id="Toggle_Sign_In_Form">
        <h6 class="position-absolute start-50 top-50 translate-middle" id="Sign_In_Text">SIGN IN</h6>
        <h6 class="position-absolute start-50 top-50 translate-middle" id="Homepage_Text">HOMEPAGE</h6>
    </div>

    <section class="bg-white position-relative text-dark vh-100">
        <article class="w-100">
            <h1 class="mb-5 text-center text-info text-sm-start"><strong>ACCOUNT SIGN IN</strong></h1>

            <form action="functions/homepage/sign-in.php" id="Sign_In_Form" method="POST">
                <label for="Email_Address">Email Address:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Email_Address" placeholder="e.g. johnsmith@hogwarts.edu" type="email">

                    <span class="position-absolute"><i class="fas fa-at"></i></span>
                </div>

                <label class="mt-4" for="Password">Password:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Password" type="password">

                    <span class="position-absolute"><i class="fas fa-lock"></i></span>
                </div>

                <div class="mt-2">
                    <input id="Show_Password" type="checkbox">

                    <label for="Show_Password">Show Password</label>
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 py-3 text-white w-100" id="Sign_In" type="submit"><b>SIGN IN</b> <i class="fas fa-sign-in-alt"></i></button>

                <div class="mt-1 row">
                    <p class="col"><a data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Sign_Up_Form">Sign Up</a></p>
                    <p class="col text-end"><a data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Forgot_Password_Form">Forgot Password</a></p>
                </div>
            </form>

            <div class="mt-3" id="Sign_In_Message"></div>
        </article>
    </section>
</aside>