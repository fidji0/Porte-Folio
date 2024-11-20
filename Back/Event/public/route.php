<?php
$router->map('GET', '/read', 'read');
$router->map('POST | PUT ', '/update', 'update');
$router->map('POST | PUT ', '/validate', 'validate');
$router->map('POST | PUT ', '/validateWeek', 'validateWeek');
$router->map('DELETE', '/delete', 'delete');
$router->map('DELETE', '/deleteWeek', 'deleteWeek');
$router->map('POST', '/create', 'create');

$router->map('GET', '/readEmploye', 'employe/read');
$router->map('POST', '/createEmploye', 'employe/create');


$router->map('GET', '/readNotif', 'employe/readNotif');
$router->map('POST', '/updateNotif', 'employe/notifReadActive');


$router->map('POST', '/createWeek', 'week_template/create');


$router->map('GET', '/version', 'version');


$router->map('POST', '/timeSheetCreate', 'timeSheet/create');
