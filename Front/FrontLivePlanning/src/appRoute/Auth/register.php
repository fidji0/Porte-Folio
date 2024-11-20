<?php

use App\Controller\Curl;

if (isset(
    $_POST["password"],
    $_POST["copyPassword"],
    $_POST["email"],
    $_POST["social"],
    $_POST["boutiqueName"],
    $_POST["siret"],
    $_POST["adress"],
    $_POST["zipCode"],
    $_POST["city"],
    $_POST["phoneNumber"],
    $_POST["accept"],
)) {
    $data = $_POST;
    $auth = new Curl;
    if ($_POST["password"] === $_POST["copyPassword"]) {
        if ($auth->inscriptionUser($data)) {
            $message = "Votre inscription a bien été prise en compte, penser à vérifier vos mails afin d' activer votre compte!";
            $succes = true;
        } else {
            isset($auth->message) ? $message = $auth->message : $message = "Une erreur s'est produite lors de votre inscription. Si vous ne parvenez pas à vous inscrire, contactez le service client.";
            $error = true;
        }
    } else {
        $message = "Les mots de passe ne correspondent pas";
        $error = true;
    }
}
require_once DIRVUE . "/template/Auth/register.php";
