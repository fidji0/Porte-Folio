<?php

use App\Controller\AbsenceController;
use App\Controller\EmployeController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'], $data['employe_id'], $data['start_date'], $data['end_date'], $data['type'] ,$data['boutique_id'])) {
    $controller = new AbsenceController();
    $response = $controller->updateAbsence($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
