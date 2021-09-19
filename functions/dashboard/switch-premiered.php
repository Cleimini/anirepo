<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Year_Premiered"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <div class="mb-5 text-center text-danger user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO SWITCH PREMIERED</strong></h4>

            <p>Refresh the page.</p>
        </div>

        <?php
    } else {
        $Year_Premiered = trim(filter_var($_POST["Year_Premiered"], FILTER_SANITIZE_NUMBER_INT));
        $Wildcard_Year_Premiered = "%" . $Year_Premiered . "%";

        if (!is_numeric($Year_Premiered)) {
            ?>

            <div class="mb-5 text-center text-danger user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>YEAR PREMIERED SHOULD BE NUMERIC</strong></h4>

                <p>Refresh the page.</p>
            </div>

            <?php
        } else {
            try {
                $Select_Premiered = $Anirepo->prepare("SELECT
                    COUNT(CASE WHEN Premiered LIKE '%Winter%' THEN 0 ELSE NULL END) AS Total_Winter_Anime,
                    COUNT(CASE WHEN Premiered LIKE '%Spring%' THEN 0 ELSE NULL END) AS Total_Spring_Anime,
                    COUNT(CASE WHEN Premiered LIKE '%Summer%' THEN 0 ELSE NULL END) AS Total_Summer_Anime,
                    COUNT(CASE WHEN Premiered LIKE '%Autumn%' THEN 0 ELSE NULL END) AS Total_Autumn_Anime
                    FROM anime
                    WHERE User_ID = :User_ID
                    AND Premiered LIKE :Premiered");
                $Select_Premiered->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Premiered->bindParam(":Premiered", $Wildcard_Year_Premiered);
                $Select_Premiered->execute();
                $Premiered = $Select_Premiered->fetch();

                $Total_Premiered = $Premiered["Total_Winter_Anime"] + $Premiered["Total_Spring_Anime"] + $Premiered["Total_Summer_Anime"] + $Premiered["Total_Autumn_Anime"];

                if ($Total_Premiered < 1 || $Select_Premiered->rowCount() < 1) {
                    ?>

                    <div class="mb-5 text-center text-info user-select-none">
                        <h1 class="Large-Font-Size"><i class="fab fa-searchengin"></i></h1>
                        <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY FROM THE SELECTED YEAR</strong></h4>
                    </div>

                    <?php
                } else {
                    ?>

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

                    <?php
                }
            } catch(PDOException $Select_Premiered) {
                ?>

                <div class="mb-5 text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_PREMIERED</strong></h4>

                    <p><?= $Select_Premiered->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }
    }

    $Anirepo = null;
?>