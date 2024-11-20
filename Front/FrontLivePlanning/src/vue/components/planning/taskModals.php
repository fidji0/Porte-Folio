<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="taskModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>Détails de l'événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <h4 class="text-info" id="taskmodalTitle"></h4>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-clock text-muted me-2"></i>
                    <div>
                        <p class="mb-0"><strong>Début:</strong> <span id="taskmodalStart"></span></p>
                        <p class="mb-0"><strong>Fin:</strong> <span id="taskmodalEnd"></span></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                    <p class="mb-0"><strong>Lieu:</strong> <span id="taskmodalLocation"></span></p>
                </div>
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-muted me-2 mt-1"></i>
                    <p class="mb-0"><strong>Description:</strong> <span id="taskDescription"></span></p>
                </div>
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-muted me-2 mt-1"></i>
                    <p class="mb-0"><strong>Nombre Mini Personnel:</strong> <span id="taskMin"></span></p>
                </div>
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-muted me-2 mt-1"></i>
                    <p class="mb-0"><strong>Nombre max personnel:</strong> <span id="taskMax"></span></p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-danger" id="deletetaskBtn">
                    <i class="fas fa-trash-alt me-2"></i>Supprimer
                </button>
                <button type="button" class="btn btn-primary" id="edittaskBtn">
                    <i class="fas fa-edit me-2"></i>Modifier
                </button>
                <button type="button" class="btn btn-primary" id="duplicatetaskBtn">
                    <i class="fas fa-copy me-2"></i>Dupliquer
                </button>

            </div>
        </div>
    </div>
</div>


<!-- modale d'edition d'évènement -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editEventModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier l'événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editTaskForm">
                    <input type="hidden" name="id" id="taskedit_event_id">
                    <div class="mb-3">
                        <label for="edit_start_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de début <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="taskedit_start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_end_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de fin <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="taskedit_end_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_objet" class="form-label"><i class="fas fa-tag me-2"></i>Objet <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="taskedit_objet" name="objet" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_lieu" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Lieu <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="taskedit_lieu" name="lieu" required>
                    </div>
                    <?php
                    
                    if (isset($skills) && count(json_decode($skills,true))>0) : ?>
                        <div class="mb-3">
                            <label for="newSkill" class="form-label">Ajouter une compétence</label>
                            <div class="input-group">
                                <select id="newSkill" name="newSkill" class="form-select">
                                    <option value="">Sélectionner une compétence</option>
                                    <?php if (isset($skills) && is_array(json_decode($skills, true))) : ?>
                                        <?php foreach (json_decode($skills, true) as $skill) : ?>
                                            <option value="<?= $skill["id"] ?>"><?= $skill["name"] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Pas de compétence disponible</option>
                                    <?php endif; ?>
                                </select>
                                <button type="button" onclick="addSkill(currentTaskId)" class="btn btn-primary" id="addSkillButton">Ajouter</button>
                            </div>
                        </div>


                        <!-- Compétences existantes -->
                        <div class="mb-3">
                            <label for="skills" class="form-label">Compétences</label>
                            <div id="skillsList">

                                
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Ajoutez ce champ dans vos formulaires de création et de modification d'événement -->
                    <div class="mb-3">
                        <label for="event_type" class="form-label">Type d'événement</label>
                        <select class="form-select" id="taskedit_event_type" name="type">
                            <option value="">Sélectionnez un type</option>
                            <option value="FOR ASSIGNED">A assigné</option>
                            <option value="FREE">Libre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_objet" class="form-label"><i class="fas fa-tag me-2"></i>Nombre minimum de personnel <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="taskedit_nbrMin" name="minPerson" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_objet" class="form-label"><i class="fas fa-tag me-2"></i>Nombre maximum de personnel <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="taskedit_nbrMax" name="maxPerson" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modale pour dupliquer l'événement -->
<div class="modal fade" id="duplicateTaskModal" tabindex="-1" aria-labelledby="duplicateEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="duplicateEventModalLabel">
                    <i class="fas fa-copy me-2"></i>Dupliquer une tache
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="duplicateTaskForm">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-calendar-week me-2"></i>Choisissez les jours de la semaine</label>
                        <div class="d-flex flex-wrap justify-content-between">
                            <?php
                            $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                            foreach ($days as $index => $day) {
                                $dayValue = ($index + 1) % 7;
                                echo "<div class='form-check form-check-inline mb-2'>
                                        <input class='form-check-input' type='checkbox' name='days[]' value='$dayValue' id='$day'>
                                        <label class='form-check-label' for='$day'>$day</label>
                                      </div>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label"><i class="fas fa-calendar-alt me-2"></i>Jusqu'au <span style="color: red;">*</span></label>
                        <input type="date" class="form-control" id="endDate" name="endDate">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Annuler</button>
                <button type="button" class="btn btn-success" id="submitTaskDuplicate"><i class="fas fa-check me-2"></i>Dupliquer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modale de confirmation de suppression -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmTaskDeleteBtn">
                    <i class="fas fa-trash-alt me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour ajouter/modifier une tache -->
<div class="modal fade" id="addTaskFormModal" tabindex="-1" aria-labelledby="TaskFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="formModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Ajouter un événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="post" class="addTaskForm" id="addTaskForm">
                    <input type="hidden" name="createTask" value="1">

                    <div class="mb-3">
                        <label for="start_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de début <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="taskstart_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de fin <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="taskend_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="objet" class="form-label"><i class="fas fa-tag me-2"></i>Objet <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="objet" name="objet" required>
                    </div>
                    <div class="mb-3">
                        <label for="lieu" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Lieu <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="lieu" name="lieu" required>
                    </div><!-- Ajoutez ce champ dans vos formulaires de création et de modification d'événement -->
                    <div class="mb-3">
                        <label for="event_type" class="form-label">Type d'événement</label>
                        <select class="form-select" id="event_type" name="type">
                            <option value="">Sélectionnez un type</option>
                            <option value="FOR ASSIGNED">A assigné</option>
                            <option value="FREE">Libre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nbrmin" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Nombre de personnel minimum <span style="color: red;">*</span></label>
                        <input type="number" class="form-control" id="nbrmin" name="minPerson">
                    </div>
                    <div class="mb-3">
                        <label for="nbrMax" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Nombre de personnel max</label>
                        <input type="number" class="form-control" id="nbrMax" name="maxPerson">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Enregistrer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>