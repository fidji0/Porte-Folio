<?php

namespace App\Controller;

class Request extends Curl
{

    public function readRequest()
    {
        $headers = array(
            'Content-Type: application/json', // Exemple d'en-tête JSON
            "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
        );
        $this->commonCurlGet(URLABS . "/readAll?boutique_id=" . $_SESSION["idBoutique"], $headers, 200);
        $res =json_decode($this->returnServer, true);
        if (isset($res["result"]) && $res["result"] == false) {
            return [];
        }
       
        return $res;
    }

    public function createRequest(
        string $name,
        string $surname,
        $email,
        $password,
        $phone,
        $solde_conges,
        $contrat,
        $color
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded', // Exemple d'en-tête JSON
                "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
            );
            $data = [
                'name' => $name,
                'surname' => $surname,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'solde_conges' => $solde_conges,
                'contrat' => $contrat,
                'color' => $color,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLEMPLOYE . "/create", 201, $headers);
        }
    }


    public function updateRequest(
        $id,
        $etat
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded', // Exemple d'en-tête JSON
                "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
            );
            $data = [
                "id" => $id,
                'etat' => $etat,
                'boutique_id' => $_SESSION["idBoutique"]
            ];
            if (isset($password) && !empty($password)) {
                $data["password"] = $password;
            }

            return $this->commonCurlPOST($data, URLABS . "/validate", 200, $headers);
        }
    }

    
}
