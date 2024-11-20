<?php

use App\Controller\Boutique;
use App\Controller\Employe;
use App\Controller\Request;
try {
  

if ($connected === false) {
    echo '<script>window.location.href = "login";</script>';
    exit();
}

$employe = new Employe();
$emp = $employe->readEmploye();

$request = new Request();
$reqs = $request->readRequest();



//cas update absence
if (!empty($_POST) && isset($_POST["action"] , $_POST["request_id"] ) ) {
    $request->updateRequest( $_POST["request_id"] ,$_POST["action"] );
}

if (!isset($_POST) || empty($_POST)) {


    $bout = $_SESSION["boutique"];

        

    include_once DIRVUE . "/template/Planning/demande.php";
}
} catch (\Throwable $th) {
    file_put_contents(DIR . "/error/fichier.log",date("d-m-Y H:i:s") .  $th . "\n", FILE_APPEND);
}