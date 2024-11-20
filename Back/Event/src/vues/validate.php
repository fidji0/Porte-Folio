<?php
use App\Controller\EventController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'],  $data['boutique_id'])) {
    $controller = new EventController();
    $response = $controller->validateEvent($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
