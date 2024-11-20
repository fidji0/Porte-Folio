<?php

use App\Controller\SkillController;


if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['id'], $data['boutique_id'], $data['name'])) {
    $controller = new SkillController();
    $response = $controller->updateSkill($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
