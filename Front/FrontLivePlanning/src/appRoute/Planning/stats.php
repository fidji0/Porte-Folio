<?php

use App\Controller\Boutique;
use App\Controller\Employe;
use App\Controller\Event;

if ($connected === false) {
    echo '<script>window.location.href = "login";</script>';
    exit();
}


$event = new Event();
$employe = new Employe();
$emp = $employe->readEmploye();
$ev = $event->readEvent();
$allEmployeeStatsValidate = $employe->calculateAllEmployeeStatsWithoutInactive(json_decode($ev, true), $emp);
$allEmployeeStats = $employe->calculateAllEmployeeStats(json_decode($ev, true), $emp);

if (!isset($_POST) || empty($_POST)) {


    $bout = $_SESSION["boutique"];

    include_once DIRVUE . "/template/Planning/stats.php";
}
