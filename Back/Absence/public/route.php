<?php
$router->map('GET', '/read', 'read');
$router->map('GET', '/readAll', 'readAll');

$router->map('POST | PUT ', '/update', 'update');

$router->map('DELETE', '/delete', 'delete');

$router->map('POST', '/create', 'create');

$router->map('POST', '/connexion', 'connexion');

$router->map('POST', '/validate', 'validate');

//user

$router->map('GET', '/readAllAbsence', 'user/readAll');
$router->map('POST', '/user_create', 'user/create');
$router->map('POST | PUT ', '/updateAbsenceEmploye', 'user/update');
$router->map('DELETE', '/deleteAbsenceEmploye', 'user/delete');
