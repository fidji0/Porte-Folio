<?php

use App\Controller\SkillController;
use App\Controller\TaskController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_GET;

if (isset($token, $data['boutique_id'])) {
    $controller = new SkillController();
    $response = $controller->readSkill($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id manquant ou invalide']);
}
