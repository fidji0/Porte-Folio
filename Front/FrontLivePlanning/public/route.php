<?php
// gestion des utilisateurs


$router->map('GET || POST', '/login', 'Auth/login');
$router->map('GET || POST', '/mdp', 'Auth/mdpOublie');
$router->map('GET || POST', '/register', 'Auth/register');
$router->map('GET || POST', '/reinsmdp', 'Auth/reinsMdp');
$router->map('GET', '/activation', 'Auth/activation');
$router->map('GET', '/disconnected', 'Auth/disconnected');


$router->map('GET', '/cgu', 'mention/cguPro');


$router->map('GET || POST', '/', 'onePage');
$router->map('GET || POST', '/adsForms', 'onePage/formAds');
$router->map('GET || POST', '/convert', 'onePage/convert');
$router->map('GET || POST', '/essai', 'onePage/convert');
$router->map('GET || POST', '/tarif', 'onePage/tarif');


$router->map('GET || POST', '/personnel', 'Planning/global');
$router->map('GET || POST', '/planning', 'Planning/planning');
$router->map('GET || POST', '/demande', 'Planning/demande');
$router->map('GET || POST', '/stats', 'Planning/stats');
$router->map('GET || POST', '/assistance', 'Planning/assistance');
$router->map('GET || POST', '/abonnement', 'Planning/abonnement');

//$router->map('POST', '/updateAuth', 'Auth/updateAuth');
//
//// pre inscription 
//$router->map('POST', '/preInscription' , 'Pre_inscription/pre_inscription');
//
//$router->map('GET' , '/readPreInsc' , 'Pre_inscription/read_pre_inscription');
//$router->map('GET' , '/readBoutique' , 'Boutique/readBoutique');
//// option a ne pas toucher 
//$router->map('OPTIONS', '*', 'option');
//
////gestion des offres
//
//$router->map('GET', '/readOffre' , 'Offres/read');
//$router->map('POST', '/addOffre' , 'Offres/create');
//$router->map('PUT', '/updateOffre' , 'Offres/update');
//
//// gestion des paiements
//$router->map('POST', '/payment', 'Payment/createPayment');
//$router->map('POST', '/success', 'Payment/successPayment');
//$router->map('POST', '/echec', 'Payment/echecPayment');
//
//
//$router->map('GET', '/encode' , 'encode');


?>