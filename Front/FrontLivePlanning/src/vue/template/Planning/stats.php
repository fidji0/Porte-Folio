<?php
include_once DIRVUE . "/elements/head.php";
?>
<style>
    
</style>
<div id="wrapper" class="d-flex flex-row">
    <?php
    include_once DIRVUE . "/elements/sideBar.php";
    ?>
    <div class="tab-pane w-100 mx-5 mt-5" id="stats" role="tabpanel">
        <div class="row mb-3">
            <div class="col-md-4">
                <select id="employeeSelect" class="form-select" multiple>
                    <?php foreach ($emp as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>" selected><?php echo $employee['name'] . ' ' . $employee['surname']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select id="eventTypeSelect" class="form-select">
                    <option value="all">Tous les types</option>
                    <?php foreach (EVENT_TYPES as $key => $value): ?>
                        <option value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <div class="btn-group" role="group" aria-label="Navigation statistiques">
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
                <div class="btn-group" role="group" aria-label="Changer de vue">
                    <button id="dailyView" class="btn btn-outline-primary active">Jour</button>
                    <button id="weeklyView" class="btn btn-outline-primary">Semaine</button>
                    <button id="monthlyView" class="btn btn-outline-primary">Mois</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <canvas id="statsChart"></canvas>
            </div>
        </div>

        
        <button id="export" class="btn btn-primary">Exporter les heures hebdomadaires</button>

        <!-- Modale Bootstrap pour sélectionner les dates -->
        <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dateModalLabel">Sélectionner les dates</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="dateForm">
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Date de début:</label>
                                <input type="date" class="form-control" id="startDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Date de fin:</label>
                                <input type="date" class="form-control" id="endDate" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-success" id="confirmDates">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

</div>

<?php

include_once DIRVUE . "/elements/footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const allEmployeeStats = <?php echo json_encode($allEmployeeStats); ?>;
    const allEmployeeStatsValidate = <?php echo json_encode($allEmployeeStatsValidate); ?>;


    const employeeColors = <?php echo json_encode(array_column($emp, 'color', 'id')); ?>;
    const EVENT_TYPES = <?php echo json_encode(EVENT_TYPES); ?>;
</script>
<script src="/js/planning/stats.js"></script>