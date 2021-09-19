<section class="m-3">
    <?php
        try {
            $Select_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Seasonal'");
            $Select_Trendings->execute();
            $Trendings = $Select_Trendings->fetchAll();

            if ($Select_Trendings->rowCount() < 1) {
                ?>

                <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>NO TRENDING ANIME THIS SEASON</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="text-center text-md-start"><strong>Trending Anime on MAL</strong></h4>

                    <form action="functions/dashboard/switch-trendings.php" class="mb-5 mt-2" method="POST">
                        <div class="form-group position-relative">
                            <select class="form-control" id="Trending_Type">
                                <option value="Seasonal">Seasonal Anime</option>
                                <option value="Airing">Currently Airing</option>
                                <option value="Updated">Updated Episodes</option>
                                <option value="Upcoming">Upcoming Anime</option>
                                <option value="Popular">All-time Popular</option>
                            </select>

                            <span class="position-absolute" style="top: 15px;"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                    </form>

                    <div id="Trendings_Placeholder">
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
                    </div>
                </article>

                <?php
            }
        } catch(PDOException $Select_Trendings) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_TRENDINGS</strong></h4>
                
                <p><?= $Select_Trendings->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>