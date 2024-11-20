<div class="card skill-section" style="max-height: 400px; height:400px; overflow-y: auto;">
    <div class="card-body">
        <h2 class="card-title text-center mb-4">Compétences</h2>

        <!-- Liste des compétences -->
        <div id="skill-list">
            <?php if ($skills) : ?>
                <?php foreach (json_decode($skills, true) as $skill) : ?>
                    <div class="skill-card card mb-3" style="border-left-color: #007bff;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= $skill["name"] ?></h5>
                            <div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modifSkill<?= $skill["id"] ?>">Modifier</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSkill<?= $skill["id"] ?>">Supprimer</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modals for Modification and Deletion of Skill -->
                    <div class="modal fade" id="modifSkill<?= $skill["id"] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier Compétence</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="skillForm" method="post">
                                        <input type="hidden" name="updateSkill" value="true">
                                        <input type="hidden" name="id" value="<?= $skill["id"] ?>">
                                        <div class="mb-3">
                                            <label for="skillName<?= $skill["id"] ?>" class="form-label">Nom de la compétence</label>
                                            <input type="text" class="form-control" id="skillName<?= $skill["id"] ?>" name="name" value="<?= $skill["name"] ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Suppression Compétence -->
                    <div class="modal fade" id="deleteSkill<?= $skill["id"] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmation de suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer cette compétence ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="post" class="skillForm">
                                        <input type="hidden" name="id" value="<?= $skill["id"] ?>">
                                        <input type="hidden" name="deleteSkill" value="true">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#addSkillModal">Ajouter une compétence</button>

    </div>
</div>

<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Ajouter une compétence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="skillForm" class="skillForm" method="post">
                    <input type="hidden" name="createSkill" value="true">
                    <div class="mb-3">
                        <label for="skillName" class="form-label">Nom de la compétence</label>
                        <input type="text" class="form-control" id="skillName" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>