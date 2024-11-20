<?php
include_once DIRVUE . "/elements/head.php";
?>
<link rel="stylesheet" href="css/planning.css">
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex flex-row">
        <?php
        include_once DIRVUE . "/elements/sideBar.php";
        ?>
        <div class="container mt-5">
            <div id="page-content-wrapper" class="flex-grow-1">
                <div class="container-fluid px-4">
                    <h1 class="h3 mb-4 text-gray-800">Planning des Salariés</h1>

                    <!-- Controls Row -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-4">
                            <div class="btn-group" role="group" aria-label="Navigation calendrier">
                                <button id="prevPeriod" class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button id="today" class="btn btn-outline-primary">Aujourd'hui</button>
                                <button id="nextPeriod" class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 id="currentPeriod" class="mb-0"></h4>
                        </div>
                        <div class="col-md-4 text-end">
                            <button id="toggleView" class="btn btn-primary">
                                <i class="fas fa-exchange-alt me-2"></i>Changer de vue
                            </button>
                        </div>
                    </div>

                    <!-- Filters Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="input-group-text" for="event-type-select">Type d'événement</label>
                                <select class="form-select" id="event-type-select">
                                    <option value="ALL">Tous</option>
                                    <option value="TRAVAIL">Travail</option>
                                    <option value="DEPLACEMENT">Déplacement</option>
                                    <option value="CONGES">Congés</option>
                                    <option value="MALADIE">Maladie</option>
                                    <option value="FORMATION">Formation</option>
                                    <option value="AUTRE">Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#formModal">
                                <i class="fas fa-plus me-2"></i>Ajouter un événement
                            </button>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body p-0">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <?php include_once DIRCOMPONENT . "/planning/taskModals.php" ?>
            <?php include_once DIRCOMPONENT . "/planning/eventModals.php" ?>

            <!-- Conteneur du spinner -->
            <div id="loadingSpinnerContainer" hidden style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Calendar -->

    <?php

    include_once DIRVUE . "/elements/footer.php";
    ?>
    <script>
        var taskOption = <?php if (isset($taskOption) && $taskOption == true) {
                                echo 1;
                            } else {
                                echo 0;
                            }  ?>;
        var events = <?php echo $ev; ?>;
        var allEmployeeStats = <?php echo json_encode($allEmployeeStats); ?>;
        var emp = <?php echo json_encode($emp); ?>;
        var tasks = <?php echo $tasks ?>;

        
    </script>
    <script src="/js/planning/planning.js"></script>
    <script src="/js/planning/planning2.js"></script>
    <script src="/js/planning/task.js"></script>
    <script src="/js/planning/draggable.js"></script>
</body>


</html>