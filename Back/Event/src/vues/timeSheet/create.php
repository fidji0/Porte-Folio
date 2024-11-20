<?php
/**
 * week number format YYYY
 */
use App\Controller\TimeSheetController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données POST
$data = $_POST;

if (isset($token, $data['employe_id'], $data['boutique_id'], $data['week_number']) && preg_match('/^\d{4}-(0[1-9]|[1-4][0-9]|5[0-3])$/', $data['week_number'])) {
    $controller = new TimeSheetController();
    $response = $controller->createTimeSheet($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
