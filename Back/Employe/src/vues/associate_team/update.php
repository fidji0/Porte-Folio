<?php
use App\Controller\EmployeController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'], $data['name'], $data['surname'], $data['email'], $data['phone'], $data['boutique_id'], $data['solde_conges'], $data['contrat'], $data['color'])) {
    $controller = new EmployeController();
    $response = $controller->updateEmploye($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
