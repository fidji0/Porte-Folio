<?php
include_once DIRVUE . "/elements/head.php";

?>

<body id="page-top">

    <div id="wrapper" class="d-flex flex-row">
        <?php include_once DIRVUE . "/elements/sideBar.php"; ?>

        <div class="container-fluid mt-5">
            <h1 class="mb-4 text-center">Gestion des Demandes</h1>

            <!-- Onglets pour les différentes catégories de demandes -->
            <ul class="nav nav-tabs mb-4" id="requestTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab">En attente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-bs-toggle="tab" href="#approved" role="tab">Validées</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rejected-tab" data-bs-toggle="tab" href="#rejected" role="tab">Refusées</a>
                </li>
            </ul>

            <!-- Contenu des onglets -->
            <div class="tab-content" id="requestTabsContent">
                <!-- Demandes en attente -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <?php echo createRequestTable($reqs, $emp, "en attente"); ?>
                </div>

                <!-- Demandes validées -->
                <div class="tab-pane fade" id="approved" role="tabpanel">
                    <?php echo createRequestTable($reqs, $emp, "valide"); ?>
                </div>

                <!-- Demandes refusées -->
                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    <?php echo createRequestTable($reqs, $emp, "refuse"); ?>
                </div>
            </div>

            <!-- Modales pour chaque demande -->
            <?php foreach ($reqs as $req):
                $employee = array_filter($emp, function ($e) use ($req) {
                    return $e['id'] == $req['employe_id'];
                });
                $employee = reset($employee);
            ?>
                <div class="modal fade" id="requestModal<?php echo $req['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel<?php echo $req['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="requestModalLabel<?php echo $req['id']; ?>">Détails de la demande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Employé:</strong> <?php echo $employee['name'] . ' ' . $employee['surname']; ?></p>
                                <p><strong>Type:</strong> <?php echo $req['type']; ?></p>
                                <p><strong>Date de la demande:</strong> <?php echo date('d/m/Y H:m', strtotime($req['created_at'])); ?></p>
                                <p><strong>Date de début:</strong> <?php echo date('d/m/Y  H:m', strtotime($req['start_date'])); ?></p>
                                <p><strong>Date de fin:</strong> <?php echo date('d/m/Y  H:m', strtotime($req['end_date'])); ?></p>
                                <p><strong>Objet:</strong> <?php echo $req['objet']; ?></p>
                                <p><strong>État:</strong> <?php echo ucfirst($req['etat']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <?php if ($req['etat'] == "en attente"): ?>
                                    <form class="requestForm" method="post">
                                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                        <input type="hidden" name="action" value="valide">
                                        <button type="submit" name="action" value="approve" class="btn btn-success">Approuver</button>

                                    </form>
                                    <form class="requestForm" method="post">
                                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                        <input type="hidden" name="action" value="refuse">

                                        <button type="submit" name="action" value="reject" class="btn btn-danger">Refuser</button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include_once DIRVUE . "/elements/footer.php"; ?>
    </div>

    <script>
        document.querySelectorAll('.requestForm').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            throw new Error('La requête a échoué');
                        }
                    })
                    .catch(error => {
                        alert("Une erreur s'est produite");
                        console.error('Erreur:', error);
                    });
            });
        });
    </script>

</body>

</html>

<?php
// Fonction pour créer le tableau des demandes
function createRequestTable($reqs, $emp, $status)
{
    // Trier les demandes par id décroissant (de la plus récente à la plus ancienne)
    usort($reqs, function ($a, $b) {
        return $b['id'] - $a['id'];
    });

    $html = '<div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">' . getStatusLabel($status) . '</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Date de la demande</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Objet</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>';

    foreach ($reqs as $req) {
        if ($req['etat'] == $status) {
            $employee = array_filter($emp, function ($e) use ($req) {
                return $e['id'] == $req['employe_id'];
            });
            $employee = reset($employee);

            $html .= '<tr>
                        <td>' . htmlspecialchars($employee['name'] . ' ' . $employee['surname']) . '</td>
                        <td>' . htmlspecialchars($req['type']) . '</td>
                        <td>' . date('d/m/Y H:i', strtotime($req['created_at'])) . '</td>
                        <td>' . date('d/m/Y H:i', strtotime($req['start_date'])) . '</td>
                        <td>' . date('d/m/Y H:i', strtotime($req['end_date'])) . '</td>
                        <td>' . htmlspecialchars($req['objet']) . '</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#requestModal' . htmlspecialchars($req['id']) . '">
                                Voir détails
                            </button>
                        </td>
                    </tr>';
        }
    }

    $html .= '</tbody></table></div></div></div>';
    return $html;
}


// Fonction pour obtenir le libellé du statut
function getStatusLabel($status)
{
    switch ($status) {
        case "en attente":
            return "Demandes en attente de validation";
        case "valide":
            return "Demandes validées";
        case "refuse":
            return "Demandes refusées";
        default:
            return "Statut inconnu";
    }
}
?>