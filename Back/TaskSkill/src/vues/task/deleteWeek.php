<?php
use App\Controller\EventController;
use App\Controller\TaskController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_GET;
if (isset($token, $data['start_date'],  $data['boutique_id'])) {
    $controller = new TaskController();
    $response = $controller->deleteWeekTask($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Donnée manquante ou invalide']);
}
