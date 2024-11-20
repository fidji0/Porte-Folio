<?php

use App\Controller\Boutique;
use App\Controller\Employe;
use App\Controller\MailerController;
use App\Controller\TaskSkill;


if ($connected === false) {
    echo '<script>window.location.href = "login";</script>';
    exit();
}

try {

    $taskOption = false;
    $skillsOption = false;
    if (isset($_SESSION['options']) && in_array('tasks', $_SESSION['options'])) {
        $taskOption = true;
    }
    if (isset($_SESSION['options']) && in_array('skills', $_SESSION['options'])) {
        $skillsOption = true;
    }
    $taskSkill = new TaskSkill();
    $employe = new Employe();
    $emp = $employe->readEmploye();
    $mail = new MailerController();
    $skills = $taskSkill->readSkill();


    //cas ajout skill employe
    if ($skillsOption == true && isset(
        $_POST['skillEmp'],
        $_POST['skill_id'],
        $_POST['employe_id'],

    )) {

        $res = $taskSkill->createEmpSkill(
            $_POST['employe_id'],
            $_POST['skill_id'],
        );
        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
    }
    //Cas envoi d'un email
    if (isset(
        $_POST['mailing'],
        $_POST['name'],
        $_POST['surname'],
        $_POST['email'],
    )) {
        $res = $mail->linkMail(
            $_POST['name'] . " " .
                $_POST['surname'],
            $_SESSION["boutique"]["boutiqueName"],
            $_POST['email']
        );

        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
    }
    //cas création d'un nouvel employe
    if (isset(
        $_POST['create'],
        $_POST['name'],
        $_POST['surname'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['color'],
        $_POST['password'],
        $_POST['contrat']
    )) {
        $res = $employe->createEmploye(
            $_POST['name'],
            $_POST['surname'],
            $_POST['email'],
            $_POST['password'],
            $_POST['phone'],
            $_POST['contrat'],
            $_POST['color']
        );
        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
    }
    //cas suppression d'un nouvel employe
    if (isset(
        $_POST['delete'],
        $_POST['id']
    )) {
        $res = $employe->deleteEmploye(
            $_POST['id']
        );

        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(204);
        }
    }
    //cas update d'un employe
    if (isset(
        $_POST['id'],
        $_POST['update'],
        $_POST['name'],
        $_POST['surname'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['color'],
        $_POST['contrat'],

    )) {
        $res = $employe->updateEmploye(
            $_POST['id'],
            $_POST['name'],
            $_POST['surname'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['contrat'],
            $_POST['color'],
            $_POST['password'] ?? null
        );

        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
    }

    //cas création d'un nouveau skill
    if ($skillsOption == true && isset(
        $_POST['createSkill'],
        $_POST['name']
    )) {
        $res = $taskSkill->createSkill(
            $_POST['name']
        );
        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
    }
    //cas suppression d'un skill
    if ( $skillsOption == true && isset(
        $_POST['deleteSkill'],
        $_POST['id']
    )) {
        $res = $taskSkill->deleteSkill(
            $_POST['id']
        );
        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }
    //cas update d'un skill
    if ($skillsOption == true && isset(
        $_POST['updateSkill'],
        $_POST['id'],
        $_POST['name']
    )) {
        $res = $taskSkill->updateSkill(
            $_POST['id'],
            $_POST['name']
        );
        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }
    //cas suppression asso emp skill
    if ($skillsOption == true && isset(
        $_POST['employe_id'],
        $_POST['skill_id'],
        $_POST['deleteSkillEmp']
    )) {
        $res = $taskSkill->deleteSkillEmp(
            $_POST['employe_id'],
            $_POST['skill_id'],
        );
        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }

    if (!isset($_POST) || empty($_POST)) {



        $bout = $_SESSION["boutique"];



        include_once DIRVUE . "/template/Planning/global.php";
    }
} catch (\Throwable $th) {
    file_put_contents(DIR . "/error/fichier.log", date("d-m-Y H:i:s") .  $th . "\n", FILE_APPEND);
}
