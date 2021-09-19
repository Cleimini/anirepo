<section class="m-3 text-center">
    <?php
        try {
            $Select_Studios = $Anirepo->prepare("SELECT DISTINCT Studios,
                COUNT(*) AS Total_Per_Studios
                FROM anime
                WHERE User_ID = :User_ID
                GROUP BY Studios
                ORDER BY Total_Per_Studios DESC
                LIMIT 5");
            $Select_Studios->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Studios->execute();
            $Studios = $Select_Studios->fetchAll();

            if ($Select_Studios->rowCount() < 1) {
                ?>
            
                <article class="bg-info p-3 rounded text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY BASED ON STUDIOS</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="text-center text-md-start"><strong>Anime by Studios</strong></h4>

                    <div class="mt-3 Grid-Container Grid-2">
                        <div>
                            <div class="Chart-Container">
                                <canvas id="Studios_Chart"></canvas>

                                <script>
                                    var Studios_Chart = document.getElementById("Studios_Chart").getContext("2d");
                                    var Studios_Chart = new Chart(Studios_Chart, {
                                        data: {
                                            datasets: [{
                                                backgroundColor: ["rgba(13, 110, 253, 0.5)", "rgba(102, 16, 242, 0.5)", "rgba(111, 66, 193, 0.5)", "rgba(214, 51, 132, 0.5)", "rgba(220, 53, 69, 0.5)"],
                                                borderColor: ["#0d6efd", "#6610f2", "#6f42c1", "#d63384", "#dc3545"],

                                                data: [
                                                    <?php
                                                        foreach ($Studios as $Studio) {
                                                            $Studio_Data_Array[] = $Studio["Total_Per_Studios"];
                                                        }

                                                        echo implode(", ", $Studio_Data_Array);
                                                    ?>
                                                ],

                                                label: "Finished Anime by Studios"
                                            }],

                                            labels: [
                                                <?php
                                                    foreach ($Studios as $Studio) {
                                                        $Studio_Label_Array[] = '"' . $Studio["Studios"] . '"';
                                                    }

                                                    echo implode(", ", $Studio_Label_Array);
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
                                foreach ($Studios as $Studio) {
                                    ?>


                                    <div class="bg-danger border mb-2 mx-auto p-2 rounded row text-white">
                                        <div class="col text-start">
                                            <p><i class="fas fa-film"></i> <?= $Studio["Studios"] ?>:</p>
                                        </div>

                                        <div class="col text-end">
                                            <p><?= number_format($Studio["Total_Per_Studios"]); ?></p>
                                        </div>
                                    </div>

                                    <?php
                                }
                            ?>

                            <button class="bg-dark btn btn-sm px-5 py-3 text-white" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" id="Show_All_Studios" type="button"><b> SHOW ALL STUDIOS</b> <i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </article>

                <?php
            }
        } catch(PDOException $Select_Studios) {
            ?>
            
            <article class="bg-danger p-3 rounded text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_STUDIOS</strong></h4>

                <p><?= $Select_Studios->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>