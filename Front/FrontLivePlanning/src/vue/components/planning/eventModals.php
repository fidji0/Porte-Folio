<!-- Modale pour ajouter/modifier un événement -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="formModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Ajouter un événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="post" class="eventForm">
                    <input type="hidden" name="create" value="1">
                    <div class="mb-3">
                        <label for="employe_id" class="form-label"><i class="fas fa-user me-2"></i>Employé <span style="color: red;">*</span></label>
                        <select class="form-select" id="employe_id" name="employe_id" required>
                            <option value="">Sélectionnez un employé</option>
                            <?php foreach ($emp as $employe): ?>
                                <option value="<?php echo $employe['id']; ?>">
                                    <?php echo $employe['name'] . ' ' . $employe['surname']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de début <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de fin <span style="color: red;">*</span></label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="objet" class="form-label"><i class="fas fa-tag me-2"></i>Objet</label>
                        <input type="text" class="form-control" id="objet" name="objet">
                    </div>
                    <div class="mb-3">
                        <label for="lieu" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Lieu</label>
                        <input type="text" class="form-control" id="lieu" name="lieu">
                    </div><!-- Ajoutez ce champ dans vos formulaires de création et de modification d'événement -->
                    <div class="mb-3">
                        <label for="event_type" class="form-label">Type d'événement <span style="color: red;">*</span></label>
                        <select class="form-select" id="event_type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="TRAVAIL">Travail</option>
                            <option value="DEPLACEMENT">Déplacement</option>
                            <option value="CONGES">Congés</option>
                            <option value="MALADIE">Maladie</option>
                            <option value="FORMATION">Formation</option>
                            <option value="AUTRE">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="equivWorkTime" class="form-label">Equivalent temps de travail</label>
                        <input type="number" step="0.1" class="form-control" id="equivWorkTime" name="equivWorkTime">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Enregistrer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modale pour afficher les détails de l'événement -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="eventModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>Détails de l'événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <h4 class="text-info" id="modalTitle"></h4>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-clock text-muted me-2"></i>
                    <div>
                        <p class="mb-0"><strong>Début:</strong> <span id="modalStart"></span></p>
                        <p class="mb-0"><strong>Fin:</strong> <span id="modalEnd"></span></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                    <p class="mb-0"><strong>Lieu:</strong> <span id="modalLocation"></span></p>
                </div>
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-muted me-2 mt-1"></i>
                    <p class="mb-0"><strong>Description:</strong> <span id="modalDescription"></span></p>
                </div>


            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-danger" id="deleteEventBtn">
                    <i class="fas fa-trash-alt me-2"></i>Supprimer
                </button>
                <button type="button" class="btn btn-primary" id="editEventBtn">
                    <i class="fas fa-edit me-2"></i>Modifier
                </button>
                <button type="button" class="btn btn-primary" id="duplicateEventBtn">
                    <i class="fas fa-copy me-2"></i>Dupliquer
                </button>
                <button type="button" class="btn btn-success" id="validationButton">
                    <i class="fas fa-check me-2"></i>Valider l'événement
                </button>
            </div>
        </div>
    </div>
</div>
<!-- modale d'edition d'évènement -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editEventModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier l'événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editEventForm">
                    <input type="hidden" id="edit_event_id" name="id">
                    <div class="mb-3">
                        <label for="edit_employe_id" class="form-label"><i class="fas fa-user me-2"></i>Employé</label>
                        <select class="form-select" id="edit_employe_id" name="employe_id" required>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_start_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de début</label>
                        <input type="datetime-local" class="form-control" id="edit_start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_end_date" class="form-label"><i class="fas fa-clock me-2"></i>Date de fin</label>
                        <input type="datetime-local" class="form-control" id="edit_end_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_objet" class="form-label"><i class="fas fa-tag me-2"></i>Objet</label>
                        <input type="text" class="form-control" id="edit_objet" name="objet">
                    </div>
                    <div class="mb-3">
                        <label for="edit_lieu" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Lieu</label>
                        <input type="text" class="form-control" id="edit_lieu" name="lieu">
                    </div>
                    <!-- Ajoutez ce champ dans vos formulaires de création et de modification d'événement -->
                    <div class="mb-3">
                        <label for="event_type" class="form-label">Type d'événement</label>
                        <select class="form-select" id="edit_event_type" name="type" required>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="equivWorkTime" class="form-label">Equivalent temps de travail</label>
                        <input type="number" step="0.1" class="form-control" id="edith_equivWorkTime" name="equivWorkTime">
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
<div class="modal fade" id="duplicateEventModal" tabindex="-1" aria-labelledby="duplicateEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="duplicateEventModalLabel">
                    <i class="fas fa-copy me-2"></i>Dupliquer l'événement
                </h5>
                <button type="button" class="btn-close close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="duplicateEventForm">
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
                        <label for="endDate" class="form-label"><i class="fas fa-calendar-alt me-2"></i>Jusqu'au</label>
                        <input type="date" class="form-control" id="endDate" name="endDate">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Annuler</button>
                <button type="button" class="btn btn-success" id="submitDuplicate"><i class="fas fa-check me-2"></i>Dupliquer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modale de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
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
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash-alt me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Sélectionnez une action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Que souhaitez-vous faire avec cet élément ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="moveBtn">Déplacer</button>
                <button type="button" class="btn btn-success" id="duplicateBtn">Dupliquer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>