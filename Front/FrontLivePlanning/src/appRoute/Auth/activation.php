<?php

use App\Controller\Curl;

if (isset($_GET["token"] , $_GET['email'])) {
    $activate = new Curl();
    $res = $activate-> activateUser($_GET["token"] , $_GET['email']);

    if ($res === true) {
        echo '<script>window.location.href = "login";</script>';
        return;
    }
    echo "ERREUR";
    
}