<?php
use App\Controller\EventController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données POST
$data = $_POST;

if (isset($token,  $data['start_date'], $data['end_date'], $data['objet'], $data['type'])) {
    $controller = new EventController();
    $response = $controller->createEmployeEvent($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
