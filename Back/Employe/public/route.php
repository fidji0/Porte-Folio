<?php
$router->map('GET', '/read', 'read');
$router->map('GET', '/readAll', 'readAll');
$router->map('POST | PUT ', '/update', 'update');
$router->map('DELETE', '/delete', 'delete');
$router->map('POST', '/create', 'create');
$router->map('POST', '/connexion', 'connexion');

// Team
$router->map('GET', '/team/read', 'team/read');
$router->map('POST | PUT ', '/team/update', 'team/update');
$router->map('DELETE', '/team/delete', 'team/delete');
$router->map('POST', '/team/create', 'team/create');




