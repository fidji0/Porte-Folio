<?php
use App\Controller\EventController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'], $data['employe_id'], $data['boutique_id'], $data['start_date'], $data['end_date'], $data['type'])) {
    $controller = new EventController();
    $response = $controller->updateEvent($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
