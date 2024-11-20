<?php
$router->map('GET', '/readTask', 'task/read');
$router->map('POST | PUT ', '/updateTask', 'task/update');
$router->map('DELETE', '/deleteTask', 'task/delete');
$router->map('DELETE', '/deleteWeekTask', 'task/deleteWeek');
$router->map('POST', '/createTask', 'task/create');

$router->map('GET', '/readSkill', 'skill/read');
$router->map('POST | PUT ', '/updateSkill', 'skill/update');
$router->map('DELETE', '/deleteSkill', 'skill/delete');
$router->map('POST', '/createSkill', 'skill/create');


$router->map('GET', '/readTaskSkill', 'taskSkill/read');
$router->map('DELETE', '/deleteTaskSkill', 'taskSkill/delete');
$router->map('POST', '/createTaskSkill', 'taskSkill/create');

$router->map('GET', '/readEmpSkill', 'empSkill/read');
$router->map('DELETE', '/deleteEmpSkill', 'empSkill/delete');
$router->map('POST', '/createEmpSkill', 'empSkill/create');

