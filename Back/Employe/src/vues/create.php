<?php
use App\Controller\EmployeController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}
/**
 * name 
 * surname
 * password
 * email
 * phone
 * boutique_id
 * solde_conges
 * contrat
 */
// Récupérez les données POST
$data = $_POST;

if (isset($token, $data['name'], $data['surname'], $data['password'], $data['email'], $data['phone'], $data['boutique_id'],  $data['contrat'], $data['color'])) {
    $controller = new EmployeController();
    $response = $controller->createEmploye($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
