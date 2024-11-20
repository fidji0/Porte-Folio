<?php
use App\Controller\EventController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données POST
$data = $_POST;

if (isset($token, $data['employe_id'], $data['boutique_id'], $data['start_date'], $data['end_date'], $data['type'])) {
    $controller = new EventController();
    $response = $controller->createEvent($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
