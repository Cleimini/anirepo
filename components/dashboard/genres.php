<section class="m-3">
    <?php
        try {
            $Select_Genres = $Anirepo->prepare("SELECT
                COUNT(CASE WHEN Genres LIKE '%Action%' THEN 0 ELSE NULL END) AS Total_Action_Genres,
                COUNT(CASE WHEN Genres LIKE '%Adventure%' THEN 0 ELSE NULL END) AS Total_Adventure_Genres,
                COUNT(CASE WHEN Genres LIKE '%Cars%' THEN 0 ELSE NULL END) AS Total_Cars_Genres,
                COUNT(CASE WHEN Genres LIKE '%Comedy%' THEN 0 ELSE NULL END) AS Total_Comedy_Genres,
                COUNT(CASE WHEN Genres LIKE '%Dementia%' THEN 0 ELSE NULL END) AS Total_Dementia_Genres,

                COUNT(CASE WHEN Genres LIKE '%Demons%' THEN 0 ELSE NULL END) AS Total_Demons_Genres,
                COUNT(CASE WHEN Genres LIKE '%Drama%' THEN 0 ELSE NULL END) AS Total_Drama_Genres,
                COUNT(CASE WHEN Genres LIKE '%Ecchi%' THEN 0 ELSE NULL END) AS Total_Ecchi_Genres,
                COUNT(CASE WHEN Genres LIKE '%Fantasy%' THEN 0 ELSE NULL END) AS Total_Fantasy_Genres,
                COUNT(CASE WHEN Genres LIKE '%Game%' THEN 0 ELSE NULL END) AS Total_Game_Genres,

                COUNT(CASE WHEN Genres LIKE '%Harem%' THEN 0 ELSE NULL END) AS Total_Harem_Genres,
                COUNT(CASE WHEN Genres LIKE '%Hentai%' THEN 0 ELSE NULL END) AS Total_Hentai_Genres,
                COUNT(CASE WHEN Genres LIKE '%Historical%' THEN 0 ELSE NULL END) AS Total_Historical_Genres,
                COUNT(CASE WHEN Genres LIKE '%Horror%' THEN 0 ELSE NULL END) AS Total_Horror_Genres,
                COUNT(CASE WHEN Genres LIKE '%Josei%' THEN 0 ELSE NULL END) AS Total_Josei_Genres,

                COUNT(CASE WHEN Genres LIKE '%Kids%' THEN 0 ELSE NULL END) AS Total_Kids_Genres,
                COUNT(CASE WHEN Genres LIKE '%Magic%' THEN 0 ELSE NULL END) AS Total_Magic_Genres,
                COUNT(CASE WHEN Genres LIKE '%Martial Arts%' THEN 0 ELSE NULL END) AS Total_Martial_Arts_Genres,
                COUNT(CASE WHEN Genres LIKE '%Mecha%' THEN 0 ELSE NULL END) AS Total_Mecha_Genres,
                COUNT(CASE WHEN Genres LIKE '%Military%' THEN 0 ELSE NULL END) AS Total_Military_Genres,

                COUNT(CASE WHEN Genres LIKE '%Music%' THEN 0 ELSE NULL END) AS Total_Music_Genres,
                COUNT(CASE WHEN Genres LIKE '%Mystery%' THEN 0 ELSE NULL END) AS Total_Mystery_Genres,
                COUNT(CASE WHEN Genres LIKE '%Parody%' THEN 0 ELSE NULL END) AS Total_Parody_Genres,
                COUNT(CASE WHEN Genres LIKE '%Police%' THEN 0 ELSE NULL END) AS Total_Police_Genres,
                COUNT(CASE WHEN Genres LIKE '%Psychological%' THEN 0 ELSE NULL END) AS Total_Psychological_Genres,

                COUNT(CASE WHEN Genres LIKE '%Romance%' THEN 0 ELSE NULL END) AS Total_Romance_Genres,
                COUNT(CASE WHEN Genres LIKE '%Samurai%' THEN 0 ELSE NULL END) AS Total_Samurai_Genres,
                COUNT(CASE WHEN Genres LIKE '%School%' THEN 0 ELSE NULL END) AS Total_School_Genres,
                COUNT(CASE WHEN Genres LIKE '%Sci-Fi%' THEN 0 ELSE NULL END) AS Total_Sci_Fi_Genres,
                COUNT(CASE WHEN Genres LIKE '%Seinen%' THEN 0 ELSE NULL END) AS Total_Seinen_Genres,

                COUNT(CASE WHEN Genres LIKE '%Shoujo%' THEN 0 ELSE NULL END) AS Total_Shoujo_Genres,
                COUNT(CASE WHEN Genres LIKE '%Shoujo Ai%' THEN 0 ELSE NULL END) AS Total_Shoujo_Ai_Genres,
                COUNT(CASE WHEN Genres LIKE '%Shounen%' THEN 0 ELSE NULL END) AS Total_Shounen_Genres,
                COUNT(CASE WHEN Genres LIKE '%Shounen Ai%' THEN 0 ELSE NULL END) AS Total_Shounen_Ai_Genres,
                COUNT(CASE WHEN Genres LIKE '%Slice of Life%' THEN 0 ELSE NULL END) AS Total_Slice_of_Life_Genres,

                COUNT(CASE WHEN Genres LIKE '%Space%' THEN 0 ELSE NULL END) AS Total_Space_Genres,
                COUNT(CASE WHEN Genres LIKE '%Sports%' THEN 0 ELSE NULL END) AS Total_Sports_Genres,
                COUNT(CASE WHEN Genres LIKE '%Super Power%' THEN 0 ELSE NULL END) AS Total_Super_Power_Genres,
                COUNT(CASE WHEN Genres LIKE '%Supernatural%' THEN 0 ELSE NULL END) AS Total_Supernatural_Genres,
                COUNT(CASE WHEN Genres LIKE '%Thriller%' THEN 0 ELSE NULL END) AS Total_Thriller_Genres,

                COUNT(CASE WHEN Genres LIKE '%Vampire%' THEN 0 ELSE NULL END) AS Total_Vampire_Genres,
                COUNT(CASE WHEN Genres LIKE '%Yaoi%' THEN 0 ELSE NULL END) AS Total_Yaoi_Genres,
                COUNT(CASE WHEN Genres LIKE '%Yuri%' THEN 0 ELSE NULL END) AS Total_Yuri_Genres
                FROM anime
                WHERE User_ID = :User_ID");
            $Select_Genres->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Genres->execute();
            $Genre = $Select_Genres->fetch();

            if ($Select_Genres->rowCount() < 1) {
                ?>
            
                <article class="bg-info p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY BASED ON GENRES</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="mb-3 text-center text-md-start"><strong>Anime by Genres</strong></h4>

                    <div class="Chart-Genres-Container">
                        <canvas id="Genres_Chart"></canvas>

                        <script>
                            var Genres_Chart = document.getElementById("Genres_Chart").getContext("2d");
                            var Genres_Chart = new Chart(Genres_Chart, {
                                data: {
                                    datasets: [{
                                        backgroundColor: ["rgba(13, 110, 253, 0.5)"],
                                        borderColor: ["#0d6efd"],
                                        borderWidth: 1.3,

                                        data: [
                                            <?= $Genre["Total_Action_Genres"] ?>, <?= $Genre["Total_Adventure_Genres"] ?>, <?= $Genre["Total_Cars_Genres"] ?>, <?= $Genre["Total_Comedy_Genres"] ?>, <?= $Genre["Total_Dementia_Genres"] ?>,
                                            <?= $Genre["Total_Demons_Genres"] ?>, <?= $Genre["Total_Drama_Genres"] ?>, <?= $Genre["Total_Ecchi_Genres"] ?>, <?= $Genre["Total_Fantasy_Genres"] ?>, <?= $Genre["Total_Game_Genres"] ?>,
                                            <?= $Genre["Total_Harem_Genres"] ?>, <?= $Genre["Total_Hentai_Genres"] ?>, <?= $Genre["Total_Historical_Genres"] ?>, <?= $Genre["Total_Horror_Genres"] ?>, <?= $Genre["Total_Josei_Genres"] ?>,
                                            <?= $Genre["Total_Kids_Genres"] ?>, <?= $Genre["Total_Magic_Genres"] ?>, <?= $Genre["Total_Martial_Arts_Genres"] ?>, <?= $Genre["Total_Mecha_Genres"] ?>, <?= $Genre["Total_Military_Genres"] ?>,
                                            <?= $Genre["Total_Music_Genres"] ?>, <?= $Genre["Total_Mystery_Genres"] ?>, <?= $Genre["Total_Parody_Genres"] ?>, <?= $Genre["Total_Police_Genres"] ?>, <?= $Genre["Total_Psychological_Genres"] ?>,
                                            <?= $Genre["Total_Romance_Genres"] ?>, <?= $Genre["Total_Samurai_Genres"] ?>, <?= $Genre["Total_School_Genres"] ?>, <?= $Genre["Total_Sci_Fi_Genres"] ?>, <?= $Genre["Total_Seinen_Genres"] ?>,
                                            <?= $Genre["Total_Shoujo_Genres"] ?>, <?= $Genre["Total_Shoujo_Ai_Genres"] ?>, <?= $Genre["Total_Shounen_Genres"] ?>, <?= $Genre["Total_Shounen_Ai_Genres"] ?>, <?= $Genre["Total_Slice_of_Life_Genres"] ?>,
                                            <?= $Genre["Total_Space_Genres"] ?>, <?= $Genre["Total_Sports_Genres"] ?>, <?= $Genre["Total_Super_Power_Genres"] ?>, <?= $Genre["Total_Supernatural_Genres"] ?>, <?= $Genre["Total_Thriller_Genres"] ?>,
                                            <?= $Genre["Total_Vampire_Genres"] ?>, <?= $Genre["Total_Yaoi_Genres"] ?>, <?= $Genre["Total_Yuri_Genres"] ?>
                                        ]
                                    }],

                                    labels: [
                                        "Action", "Adventure" ,"Cars", "Comedy", "Dementia",
                                        "Demons", "Drama", "Ecchi", "Fantasy", "Game",
                                        "Harem", "Hentai", "Historical", "Horror", "Josei",
                                        "Kids", "Magic", "Martial Arts", "Mecha", "Military",
                                        "Music", "Mystery", "Parody", "Police", "Psychological",
                                        "Romance", "Samurai", "School", "Sci-Fi", "Seinen",
                                        "Shoujo", "Shoujo Ai", "Shounen", "Shounen Ai", "Slice of Life",
                                        "Space", "Sports", "Super Power", "Supernatural", "Thriller",
                                        "Vampire", "Yaoi", "Yuri"
                                    ]
                                },

                                options: {
                                    maintainAspectRatio: false,

                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },

                                    scales: {
                                        x: {
                                            grid: {
                                                color: "rgba(13, 110, 253, 0.1)"
                                            }
                                        },

                                        y: {
                                            grid: {
                                                color: "rgba(13, 110, 253, 0.1)"
                                            }
                                        }
                                    }
                                },

                                type: "bar"
                            });
                        </script>
                    </div>

                    <div class="Chart-Genres-Data-Container">
                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col"><i class="fas fa-theater-masks"></i> Action</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Action_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Adventure</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Adventure_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Cars</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Cars_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Comedy</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Comedy_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Dementia</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Dementia_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Demons</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Demons_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Drama</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Drama_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Ecchi</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Ecchi_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Fantasy</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Fantasy_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Game</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Game_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Harem</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Harem_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Hentai</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Hentai_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Historical</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Historical_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Horror</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Horror_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Josei</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Josei_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Kids</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Kids_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Magic</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Magic_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Martial Arts</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Martial_Arts_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Mecha</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mecha_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Military</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Military_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Music</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Music_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Mystery</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mystery_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Parody</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Parody_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Police</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Police_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Psychological</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Psychological_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Romance</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mystery_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Samurai</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mystery_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> School</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mystery_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Sci-Fi</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mystery_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Seinen</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Mystery_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Shoujo</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Shoujo_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Shoujo Ai</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Shoujo_Ai_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Shounen</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Shounen_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Shounen Ai</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Shounen_Ai_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Slice of Life</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Slice_of_Life_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Space</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Space_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Sports</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Sports_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Super Power</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Super_Power_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Supernatural</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Supernatural_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Thriller</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Thriller_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Vampire</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Vampire_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Yaoi</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Yaoi_Genres"]); ?> Anime</span>
                        </p>

                        <p class="bg-success mb-2 mx-auto p-2 rounded row text-white">
                            <span class="col text-start"><i class="fas fa-theater-masks"></i> Yuri</span>
                            <span class="col text-end"><?= number_format($Genre["Total_Yuri_Genres"]); ?> Anime</span>
                        </p>
                    </div>
                </article>

                <?php
            }
        } catch(PDOException $Select_Genres) {
            ?>
            
            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_GENRES</strong></h4>

                <p><?= $Select_Genres->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>