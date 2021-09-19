<section class="m-3">
    <?php
        try {
            $Select_Sources = $Anirepo->prepare("SELECT DISTINCT Source,
                COUNT(*) AS Total_Per_Sources
                FROM anime
                WHERE User_ID = :User_ID
                GROUP BY Source
                ORDER BY Total_Per_Sources DESC");
            $Select_Sources->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Sources->execute();
            $Sources = $Select_Sources->fetchAll();

            if ($Select_Sources->rowCount() < 1) {
                ?>
            
                <article class="bg-info p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY BASED ON SOURCES</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="text-center text-md-start"><strong>Anime by Sources</strong></h4>

                    <div class="mt-3 Grid-Container Grid-2">
                        <div>
                            <div>
                                <canvas id="Source_Chart"></canvas>

                                <script>
                                    var Source_Chart = document.getElementById("Source_Chart").getContext("2d");
                                    var Source_Chart = new Chart(Source_Chart, {
                                        data: {
                                            datasets: [{
                                                backgroundColor: ["rgba(13, 110, 253, 0.5)", "rgba(102, 16, 242, 0.5)", "rgba(111, 66, 193, 0.5)",
                                                    "rgba(214, 51, 132, 0.5)", "rgba(220, 53, 69, 0.5)", "rgba(253, 126, 20, 0.5)",
                                                    "rgba(255, 193, 7, 0.5)", "rgba(25, 135, 84, 0.5)", "rgba(32, 201, 151, 0.5)"],
                                                borderColor: ["#0d6efd", "#6610f2", "#6f42c1", "#d63384", "#dc3545", "#fd7e14", "#ffc107", "#198754", "#20c997"],

                                                data: [
                                                    <?php
                                                        foreach ($Sources as $Source) {
                                                            $Source_Data_Array[] = $Source["Total_Per_Sources"];
                                                        }

                                                        echo implode(", ", $Source_Data_Array);
                                                    ?>
                                                ],

                                                label: "Finished Anime by Source"
                                            }],

                                            labels: [
                                                <?php
                                                    foreach ($Sources as $Source) {
                                                        $Source_Label_Array[] = '"' . $Source["Source"] . '"';
                                                    }

                                                    echo implode(", ", $Source_Label_Array);
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
                                foreach ($Sources as $Source) {
                                    ?>

                                    <div class="bg-danger border mb-2 mx-auto p-2 rounded row text-white">
                                        <div class="col">
                                            <p><i class="fas fa-book-open"></i> <?= $Source["Source"] ?>:</p>
                                        </div>

                                        <div class="col text-end">
                                            <p><?= number_format($Source["Total_Per_Sources"]); ?></p>
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
        } catch(PDOException $Select_Sources) {
            ?>
            
            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_SOURCES</strong></h4>

                <p><?= $Select_Sources->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>