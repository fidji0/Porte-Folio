<?php
use App\Controller\EmployeController;
use App\Controller\TeamController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données POST
$data = $_GET;

if (isset($token) && !empty($data["boutique_id"])) {
    $controller = new TeamController();
    $response = $controller->readAllTeam($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
