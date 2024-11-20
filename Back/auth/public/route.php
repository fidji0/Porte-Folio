<?php

$router->map('POST', '/sign_up', 'inscription', 'inscription');
//authentification 
$router->map('POST', '/auth', 'auth', 'auth');
//activation
$router->map('PUT', '/activation', 'activationAccount', 'activationAccount');

// changement mot de passe
$router->map('POST', '/changePassword', 'changePassword', 'changePassword');


$router->map('POST', '/changePasswordForget' , 'changePasswordForget', 'changePasswordForget');

$router->map('POST', '/forgetPassword', 'forgetPassword', 'forgetPassword');

$router->map('POST', '/replacePassword', 'replacePassword', 'replacePassword');

$router->map('POST', '/deleteUser', 'deleteUser', 'deleteUser');

$router->map('POST', '/refreshToken', 'refreshToken', 'refreshToken');
//