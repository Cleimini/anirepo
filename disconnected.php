<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include_once "components/head.html"; ?>

        <style>
            body {
                background-attachment: fixed;
                background-image: url("images/backgrounds/613bf50a989a5_20210911081556.png");
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            section {
                background-color: var(--bs-purple);
                padding: 15px;
                width: 450px;
            }

            @media only screen and (max-width: 480px) {
                section {
                    padding: 5px;
                    width: 95%;
                }
            }
        </style>

        <title>AniRepo: Disconnected</title>
    </head>

    <body class="text-center text-white user-select-none">
        <?php
            if (!$Disconnected) {
                header("Location: index.php");
            } else {
                ?>

                <main class="position-relative vh-100">
                    <section class="border border-white start-50 position-absolute rounded shadow-lg top-50 translate-middle">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>DATABASE ERROR</strong></h4>

                        <p><?= $Disconnected_Message->getMessage(); ?>.</p>
                    </section>
                </main>

                <?php
            }
        ?>
    </body>
</html>