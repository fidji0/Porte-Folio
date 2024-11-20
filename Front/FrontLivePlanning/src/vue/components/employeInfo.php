<div class="card employee-section" style="max-height: 400px; overflow-y: auto;">
    <div class="card-body">
        <h2 class="card-title text-center mb-4">Salariés</h2>

        <!-- Liste des salariés -->
        <div id="employee-list">
            <?php if ($emp) : ?>
                <?php foreach ($emp as $value) : ?>
                    <div class="employee-card card mb-3" style="border-left-color: <?= $value["color"] ?>;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $value["name"] . " " . $value["surname"] ?></h5>
                            <p class="card-text">Email : <?= $value["email"] ?></p>
                            <p class="card-text">Téléphone : <?= $value["phone"] ?></p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modifEmploye<?= $value["id"] ?>">Modifier</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteEmploye<?= $value["id"] ?>">Supprimer</button>
                            <button class="btn btn-info btn-sm mail-download" data-id="<?= $value["id"] ?>" data-name="<?= $value["name"] ?>" data-surname="<?= $value["surname"] ?>" data-email="<?= $value["email"] ?>">Mail téléchargement</button>
                        </div>
                    </div>
                    <!-- Modal Suppression -->
                    <div class="modal fade" id="deleteEmploye<?= $value["id"] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmation de suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer ce salarié ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="post" class="employeeForm">
                                        <input type="hidden" name="id" value="<?= $value["id"] ?>">
                                        <input type="hidden" name="delete" value="true">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- Modale mise a jour-->
                    <div class="modal fade" id="modifEmploye<?= $value["id"] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier un salarié</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="employeeForm" method="post">
                                        <input type="hidden" id="employeeId" name="update" value="true">
                                        <input type="hidden" id="employeeId" name="id" value="<?= $value["id"] ?>">

                                        <!-- Nom -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?= $value["name"] ?? null ?>" required>
                                        </div>

                                        <!-- Prénom -->
                                        <div class="mb-3">
                                            <label for="surname" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="surname" name="surname" value="<?= $value["surname"] ?? null ?>" required>
                                        </div>

                                        <!-- Mot de passe -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mot de passe</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $value["email"] ?? null ?>" required>
                                        </div>

                                        <!-- Téléphone -->
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= $value["phone"] ?? null ?>" required>
                                        </div>


                                        <!-- Ajouter une nouvelle compétence -->
                                        <?php if ($skillsOption == true) : ?>
                                            <div class="mb-3">
                                                <label for="newSkill" class="form-label">Ajouter une compétence</label>
                                                <div class="input-group">
                                                    <select id="newSkill<?= $value['id'] ?>" name="newSkill" class="form-select">
                                                        <option value="">Sélectionner une compétence</option>
                                                        <?php if (isset($skills) && is_array(json_decode($skills, true))) : ?>
                                                            <?php foreach (json_decode($skills, true) as $skill) : ?>
                                                                <option value="<?= $skill["id"] ?>"><?= $skill["name"] ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="">Pas de compétence disponible</option>
                                                        <?php endif; ?>
                                                    </select>
                                                    <button type="button" onclick="addSkill(<?= $value['id'] ?> )" class="btn btn-primary" id="addSkillButton">Ajouter</button>
                                                </div>
                                            </div>


                                            <!-- Compétences existantes -->
                                            <div class="mb-3">
                                                <label for="skills" class="form-label">Compétences</label>
                                                <div id="skillsList">

                                                    <?php foreach ($value["skills"] as $skill) : ?>
                                                        <div class="skill-item d-flex align-items-center mb-2">
                                                            <span class="skill-name"><?= is_null($skill["name"]) ? "Aucune compétence ajouté" : htmlspecialchars($skill["name"])  ?></span>
                                                            <?php if (!is_null($skill["name"])) :   ?>
                                                                <button type="button" class="btn btn-danger btn-sm ms-2 remove-skill" onclick="deleteSkill(<?= $skill['id'] ?> , <?= $value['id'] ?>)" data-index="<?= $skill["name"] ?>">✕</button>

                                                            <?php endif;   ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <!-- Nombre d'heures par semaine -->
                                        <div class="mb-3">
                                            <label for="contrat" class="form-label">Nombre d'heures par semaine</label>
                                            <input type="number" step="0.5" class="form-control" id="contrat" name="contrat" value="<?= $value["contrat"] ?? null ?>" required>
                                        </div>

                                        <!-- Couleur associée -->
                                        <div class="mb-3">
                                            <label for="color" class="form-label">Couleur associée</label>
                                            <input type="color" class="form-control form-control-color" id="color" name="color" value="<?= $value["color"] ?? null ?>" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Ajouter un salarié</button>

    </div>
</div>
<!-- Modal pour ajouter/modifier un salarié -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Ajouter un salarié</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="employeeForm" class="employeeForm" method="post">
                                <input type="hidden" id="employeeId">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="surname" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contrat" class="form-label">Nombre d'heure par semaine</label>
                                    <input type="number" step="0.5" class="form-control" id="contrat" name="contrat" required>
                                </div>

                                <div class="mb-3">
                                    <label for="color" class="form-label">Couleur associée</label>
                                    <input type="color" class="form-control form-control-color" id="color" value="#007bff" name="color" required>
                                </div>
                                <div>
                                    <input type="hidden" name="create" value="true">
                                </div>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
