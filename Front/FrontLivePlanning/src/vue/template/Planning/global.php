<?php
include_once DIRVUE . "/elements/head.php";

?>

<body id="page-top">


    <style>
        .employee-card {
            border-left: 5px solid;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .employee-card:hover {
            transform: scale(1.02);
        }

        .card-body {
            padding: 15px;
        }
    </style>
    <main class="d-flex flex-row">
        <?php

        include_once DIRVUE . "/elements/sideBar.php";

        ?>
        <div class="container mt-5">
            <section>
                <h1 class="mb-4 text-center">Gestion des Salariés et des Compétences</h1>

                <div class="row">
                    <!-- Section Salariés -->
                    <div class="<?= $skillsOption == true ? "col-lg-8" : "" ?> col-12">
                        <?php include_once DIRCOMPONENT . "/employeInfo.php" ?>
                    </div>

                    <?php if ($skillsOption == true) : ?>
                        <!-- Section Compétences (Skills) -->
                        <div class="col-lg-4 col-12 mt-5 mt-lg-0">
                            <?php include_once DIRCOMPONENT."/skillGestion.php" ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Modal pour ajouter une compétence -->
           



            <section>
                <?php include_once DIRCOMPONENT . "/enterpriseInfo.php" ?>
            </section>

            <!-- Modal de confirmation de suppression -->

        </div>




    </main>
    <script src="/js/employe/employe.js"></script>
    <?php
    include_once DIRVUE . "/elements/footer.php";
    ?>
</body>

</html>