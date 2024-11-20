<?php
use App\Controller\EventController;
use App\Controller\ModelsController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données POST
$data = $_POST;

if (isset($token, $data['name'], $data['boutique_id'], $data['week_template'])) {
    $controller = new ModelsController();
    $response = $controller->saveNewWeekModels($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
