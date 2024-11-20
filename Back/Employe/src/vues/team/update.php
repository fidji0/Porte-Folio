<?php
use App\Controller\EmployeController;
use App\Controller\TeamController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'], $data['name'],  $data['boutique_id'], $data['description'])) {
    $controller = new TeamController();
    $response = $controller->updateTeam($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
