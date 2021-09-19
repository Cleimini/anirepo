<section class="m-3">
    <?php
        try {
            $Select_Anime_Types = $Anirepo->prepare("SELECT DISTINCT Anime_Type,
                COUNT(*) AS Total_Per_Anime_Types
                FROM anime
                WHERE User_ID = :User_ID
                GROUP BY Anime_Type
                ORDER BY Total_Per_Anime_Types DESC");
            $Select_Anime_Types->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime_Types->execute();
            $Anime_Types = $Select_Anime_Types->fetchAll();

            if ($Select_Anime_Types->rowCount() < 1) {
                ?>
            
                <article class="bg-info p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY BASED ON ANIME TYPES</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="text-center text-md-start"><strong>Anime by Types</strong></h4>

                    <div class="mt-3 Grid-Container Grid-2">
                        <div>
                            <div>
                                <canvas id="Anime_Type_Chart"></canvas>

                                <script>
                                    var Anime_Type_Chart = document.getElementById("Anime_Type_Chart").getContext("2d");
                                    var Anime_Type_Chart = new Chart(Anime_Type_Chart, {
                                        data: {
                                            datasets: [{
                                                backgroundColor: ["rgba(13, 110, 253, 0.5)", "rgba(102, 16, 242, 0.5)", "rgba(111, 66, 193, 0.5)", "rgba(214, 51, 132, 0.5)", "rgba(220, 53, 69, 0.5)"],
                                                borderColor: ["#0d6efd", "#6610f2", "#6f42c1", "#d63384", "#dc3545"],

                                                data: [
                                                    <?php
                                                        foreach ($Anime_Types as $Anime_Type) {
                                                            $Anime_Type_Data_Array[] = $Anime_Type["Total_Per_Anime_Types"];
                                                        }

                                                        echo implode(", ", $Anime_Type_Data_Array);
                                                    ?>
                                                ],

                                                label: "Finished Anime by Anime Type"
                                            }],

                                            labels: [
                                                <?php
                                                    foreach ($Anime_Types as $Anime_Type) {
                                                        $Anime_Type_Label_Array[] = '"' . $Anime_Type["Anime_Type"] . '"';
                                                    }

                                                    echo implode(", ", $Anime_Type_Label_Array);
                                                ?>
                                            ]
                                        },

                                        options: {
                                            maintainAspectRatio: false,

                                            plugins: {
                                                legend: {
                                                    padding: 15,
                                                    position: "bottom"
                                                }
                                            },

                                            scales: {
                                                x: {
                                                    grid: {
                                                        color: "rgba(13, 110, 253, 0.1)"
                                                    },

                                                    ticks: {
                                                        display: false
                                                    }
                                                },

                                                y: {
                                                    grid: {
                                                        color: "rgba(13, 110, 253, 0.1)"
                                                    },

                                                    ticks: {
                                                        display: false
                                                    }
                                                }
                                            }
                                        },

                                        type: "doughnut"
                                    });
                                </script>
                            </div>
                        </div>

                        <div>
                            <?php
                                foreach ($Anime_Types as $Anime_Type) {
                                    ?>

                                    <div class="bg-danger border mb-2 mx-auto p-2 rounded row text-white">
                                        <div class="col">
                                            <p class="m-0"><i class="fas fa-book-open"></i> <?= $Anime_Type["Anime_Type"] ?>:</p>
                                        </div>

                                        <div class="col text-end">
                                            <p class="m-0"><?= number_format($Anime_Type["Total_Per_Anime_Types"]); ?></p>
                                        </div>
                                    </div>

                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </article>

                <?php
            }
        } catch(PDOException $Select_Anime_Types) {
            ?>
            
            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_ANIME_TYPES</strong></h4>

                <p><?= $Select_Anime_Types->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>