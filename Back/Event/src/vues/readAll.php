<?php
use App\Controller\EventController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['boutique_id'])) {
    $controller = new EventController();
    $response = $controller->readAllEventsByBoutique($data['boutique_id'], $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id de la boutique manquant ou invalide']);
}
