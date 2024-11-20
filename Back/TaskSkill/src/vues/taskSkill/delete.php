<?php


use App\Controller\TaskSkillController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_GET;

if (isset($token, $data['task_id'], $data['skill_id']  , $data['boutique_id'])) {
    $controller = new TaskSkillController();
    $response = $controller->deleteTaskSkill($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id manquant ou invalide']);
}
