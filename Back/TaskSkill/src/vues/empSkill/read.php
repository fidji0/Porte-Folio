<?php

use App\Controller\EmpSkillController;


if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_GET;

if (isset($token, $data['boutique_id'], $data['employe_id'] , $data['skill_id'])) {
    $controller = new EmpSkillController();
    $response = $controller->readEmpSkill($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id manquant ou invalide']);
}
