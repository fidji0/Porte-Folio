<?php

use App\Controller\TaskController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'], $data['boutique_id'], $data['start_date'], $data['end_date'], $data['objet'])) {
    $controller = new TaskController();
    $response = $controller->updateTask($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
