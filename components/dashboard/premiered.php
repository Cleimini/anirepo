<section class="m-3 text-center">
    <?php
        try {
            $Wildcard_Premiered = "%" . $Year_Premiered = date("Y") . "%";

            $Select_Premiered = $Anirepo->prepare("SELECT
                COUNT(CASE WHEN Premiered LIKE '%Winter%' THEN 0 ELSE NULL END) AS Total_Winter_Anime,
                COUNT(CASE WHEN Premiered LIKE '%Spring%' THEN 0 ELSE NULL END) AS Total_Spring_Anime,
                COUNT(CASE WHEN Premiered LIKE '%Summer%' THEN 0 ELSE NULL END) AS Total_Summer_Anime,
                COUNT(CASE WHEN Premiered LIKE '%Autumn%' THEN 0 ELSE NULL END) AS Total_Autumn_Anime
                FROM anime
                WHERE User_ID = :User_ID
                AND Premiered LIKE :Premiered");
            $Select_Premiered->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Premiered->bindParam(":Premiered", $Wildcard_Premiered);
            $Select_Premiered->execute();
            $Premiered = $Select_Premiered->fetch();

            $Select_Undefined_Premiered = $Anirepo->prepare("SELECT * FROM anime WHERE User_ID = :User_ID AND Premiered = ''");
            $Select_Undefined_Premiered->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Undefined_Premiered->execute();

            $Total_Premiered = $Premiered["Total_Winter_Anime"] + $Premiered["Total_Spring_Anime"] + $Premiered["Total_Summer_Anime"] + $Premiered["Total_Autumn_Anime"];

            if ($Total_Premiered < 1 || $Select_Premiered->rowCount() < 1) {
                ?>
                            
                <article class="bg-info p-3 rounded text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY THIS YEAR</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="text-center text-md-start"><strong>Anime by Premiered</strong></h4>

                    <form action="functions/dashboard/switch-premiered.php" class="mb-5 mt-2" method="POST">
                        <div class="form-group position-relative">
                            <select class="form-control" id="Year_Premiered">
                                <?php
                                    for ($Year = 1960; $Year <= $Year_Premiered; $Year++) {
                                        ?> <option <?php if ($Year == date("Y")) { echo "selected"; } ?> value="<?= $Year; ?>">Year <?= $Year; ?></option> <?php
                                    }
                                ?>
                            </select>

                            <span class="position-absolute" style="top: 15px;"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                    </form>

                    <div id="Premiered_Placeholder">
                        <div>
                            <canvas id="Premiered_Chart"></canvas>

                            <script>
                                var Premiered_Chart = document.getElementById("Premiered_Chart").getContext("2d");
                                var Premiered_Chart = new Chart(Premiered_Chart, {
                                    data: {
                                        datasets: [{
                                            backgroundColor: ["rgba(13, 110, 253, 0.5)", "rgba(102, 16, 242, 0.5)", "rgba(111, 66, 193, 0.5)", "rgba(214, 51, 132, 0.5)"],
                                            borderColor: ["#0d6efd", "#6610f2", "#6f42c1", "#d63384"],
                                            borderWidth: 1.3,

                                            data: [
                                                <?= $Premiered["Total_Winter_Anime"]; ?>,
                                                <?= $Premiered["Total_Spring_Anime"]; ?>,
                                                <?= $Premiered["Total_Summer_Anime"]; ?>,
                                                <?= $Premiered["Total_Autumn_Anime"]; ?>
                                            ]
                                        }],

                                        labels: ["Winter", "Spring", "Summer", "Autumn"]
                                    },

                                    options: {
                                        indexAxis: "y",
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

                        <div class="mt-5 row">
                            <div class="col-md">
                                <p class="bg-primary border mb-2 mx-auto p-2 rounded row text-white">
                                    <span class="col text-start"><i class="fas fa-snowflake"></i> Winter</span>
                                    <span class="col text-end"><?= number_format($Premiered["Total_Winter_Anime"]); ?> Anime</span>
                                </p>

                                <p class="bg-danger border mb-2 mx-auto p-2 rounded row text-white">
                                    <span class="col text-start"><i class="fas fa-rainbow"></i> Spring</span>
                                    <span class="col text-end"><?= number_format($Premiered["Total_Spring_Anime"]); ?> Anime</span>
                                </p>
                            </div>

                            <div class="col-md">
                                <p class="bg-warning border mb-2 mx-auto p-2 rounded row text-dark">
                                    <span class="col text-start"><i class="fas fa-umbrella-beach"></i> Summer</span>
                                    <span class="col text-end"><?= number_format($Premiered["Total_Summer_Anime"]); ?> Anime</span>
                                </p>

                                <p class="bg-success border mb-2 mx-auto p-2 rounded row text-white">
                                    <span class="col text-start"><i class="fas fa-mountain"></i> Autumn</span>
                                    <span class="col text-end"><?= number_format($Premiered["Total_Autumn_Anime"]); ?> Anime</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="bg-info mb-2 p-2 rounded text-center text-white"><strong>A TOTAL OF <?= number_format($Select_Undefined_Premiered->rowCount()); ?> UNDEFINED PREMIERED FROM YOUR REPOSITORY ON ALL YEARS.</strong></p>

                    <button class="bg-dark btn btn-sm px-5 py-3 text-white" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" id="Show_Undefined_Premiered" type="button"><b> SHOW UNDEFINED PREMIERED</b> <i class="fas fa-eye"></i></button>
                </article>

                <?php
            }
        } catch(PDOException $Select_Premiered) {
            ?>
            
            <article class="bg-danger p-3 rounded text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_PREMIERED</strong></h4>

                <p><?= $Select_Premiered->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>