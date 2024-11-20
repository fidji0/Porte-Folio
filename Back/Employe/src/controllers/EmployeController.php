<?php

namespace App\Controller;

use App\Class\EmployeClass;

class EmployeController extends CommonController
{

    public string $token;


    //create
    public function createEmploye(array $data, $token)
    {

        try {

            if (
                empty($data['name']) ||
                empty($data['surname']) ||
                empty($data['email']) ||
                empty($data['phone']) ||
                empty($data['boutique_id']) ||
                empty($data['color']) ||
                empty($data['contrat'])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }
            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->setResponse(false, "email invalide", 401);
            }
            $employe = new EmployeClass();
            $employe->setName($data['name']);
            $employe->setSurname($data['surname']);
            $employe->setPassword($data['password']);
            $employe->setEmail($data['email']);
            $employe->setPhone($data['phone']);
            $employe->setBoutique_id($data['boutique_id']);
            $employe->setContrat($data['contrat']);
            $employe->setColor($data['color']);

            return $employe->createNewEmploye();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }

    /**
     * 
     * read employe
     */
    public function readEmploye(array $data, $token)
    {

        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            $employe = new EmployeClass();
            $employe->setBoutique_id($data['boutique_id']);
            $employe->setId($data['id']);
            $res = $employe->readEmploye();
            return $this->structureData($res);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe : " . $th, "controller");
            return false;
        }
    }
    /**
     * 
     * read All employe
     */
    public function readAllEmploye(array $data, $token)
    {

        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            $employe = new EmployeClass();
            $employe->setBoutique_id($data['boutique_id']);
            $res = $employe->readAllEmployeBoutique();
            return $this->structureData($res);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }

    protected function structureData($data)
    {
        foreach ($data as &$emp) {
            if (isset($emp['week_templates']) && is_string($emp['week_templates'])) {
                // Décoder la chaîne JSON en tableau PHP
                $weekTemplatesData = json_decode($emp['week_templates'], true);

                // Vérifier si le décodage a réussi et si la clé 'week_templates' existe
                if (is_array($weekTemplatesData) && isset($weekTemplatesData['week_templates'])) {
                    // Remplacer la chaîne JSON par le tableau décodé
                    $emp['week_templates'] = $weekTemplatesData['week_templates'];

                    // Traiter chaque modèle de semaine
                    foreach ($emp['week_templates'] as &$template) {
                        if (isset($template['week_template']) && is_string($template['week_template'])) {
                            // Décoder le contenu de 'week_template' qui est aussi en JSON
                            $template['week_template'] = json_decode($template['week_template'], true);
                        }
                    }
                } else {
                    // Si le décodage a échoué ou si la structure n'est pas comme attendu, initialiser à un tableau vide
                    $emp['week_templates'] = [];
                }
            } else {
                // Si 'week_templates' n'existe pas ou n'est pas une chaîne, initialiser à un tableau vide
                $emp['week_templates'] = [];
            }
            
            if (isset($emp['skills']) && is_string($emp['skills'])) {
                // Décoder la chaîne JSON en tableau PHP
                $weekTemplatesData = json_decode($emp['skills'], true);

                // Vérifier si le décodage a réussi et si la clé 'week_templates' existe
                if (is_array($weekTemplatesData) && isset($weekTemplatesData['skills'])) {
                    // Remplacer la chaîne JSON par le tableau décodé
                    $emp['skills'] = $weekTemplatesData['skills'];

                    // Traiter chaque modèle de semaine
                    foreach ($emp['skills'] as &$template) {
                        if (isset($template['skill']) && is_string($template['skill'])) {
                            // Décoder le contenu de 'week_template' qui est aussi en JSON
                            $template['skill'] = json_decode($template['skill'], true);
                        }
                    }
                } else {
                    // Si le décodage a échoué ou si la structure n'est pas comme attendu, initialiser à un tableau vide
                    $emp['skills'] = [];
                }
            } else {
                // Si 'week_templates' n'existe pas ou n'est pas une chaîne, initialiser à un tableau vide
                $emp['skills'] = [];
            }
        }
        return $data;
    }
    /**
     * mise a jour des données du salarié
     */
    public function updateEmploye(array $data, $token)
    {
        if (
            empty($data['id']) ||
            empty($data['name']) ||
            empty($data['surname']) ||
            empty($data['email']) ||
            empty($data['phone']) ||
            empty($data['boutique_id']) ||
            empty($data['color']) ||
            empty($data['contrat'])
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }

        //Vérifie autorisation de l'app
        if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
            return $this->setResponse(false, "Non Autorisé", 403);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->setResponse(false, "email invalide", 401);
        }

        $employe = new EmployeClass();

        $employe->setId($data['id']);
        $employe->setName($data['name']);
        $employe->setSurname($data['surname']);
        $employe->setEmail($data['email']);
        $employe->setPhone($data['phone']);
        $employe->setBoutique_id($data['boutique_id']);
        $employe->setContrat($data['contrat']);
        $employe->setColor($data['color']);

        if (!empty($data['password'])) {
            $employe->setPassword($data['password']);
        }
        if ($employe->updateEmploye()) {
            return $this->readEmploye($data, $token);
        }

        return $this->setResponse(false, "Une erreur c'est produite", 500);

        try {
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }
    /**
     * mise a jour des données du salarié
     */
    public function deleteEmploye(array $data, $token)
    {
        if (
            empty($data['boutique_id']) ||
            empty($data['id'])
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }

        //Vérifie autorisation de l'app
        if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
            return $this->setResponse(false, "Non Autorisé", 403);
        }


        $employe = new EmployeClass();

        $employe->setId($data['id']);
        $employe->setBoutique_id($data['boutique_id']);

        if ($employe->deleteEmploye()) {
            return $this->setResponse(true, "Supprimer", 200);
        }

        return $this->setResponse(false, "Une erreur c'est produite", 500);

        try {
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Employe : " . $th, "controller");
            return false;
        }
    }
    /**
     * 
     * @var email
     * @var password
     */

    public function connexion(array $data)
    {
        try {
            $request = "SELECT e.* , b.boutiqueName AS boutiqueName , b.adress , b.zipCode , b.city , b.ste_code , b.phoneNumber FROM employe e INNER JOIN boutique b ON e.boutique_id = b.id WHERE e.email = ? AND b.ste_code = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$data['email'], $data['sct_code']]);
            $result = $r->fetch(\PDO::FETCH_ASSOC);
            if ($result === false || empty($result['password'])) {
                return $this->setResponse(false, "Identifiant ou mot de passe incorrect", 400);
            }
            $email = $result['email'];


            $request = "SELECT id , boutique_id , name , surname , email , color , contrat , soldeConges , phone FROM employe WHERE email = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$data['email']]);
            $res = $r->fetch(\PDO::FETCH_ASSOC);
            $id = $res["id"];
            $boutique_id = $res["boutique_id"];


            //Vérifie le mot de passe
            if ($this->verifyPasswordIsOk($result['password'], $data["password"])) {
                //crée un token d'authentification
                $this->createToken($id, $boutique_id);
                if (isset($data['notif_phone_id'])) {
                    $employe = new EmployeClass();
                    $employe->setEmail($data["email"]);
                    $employe->setNotif_phone_id($data["notif_phone_id"]);
                    $employe->updateNotifId();
                }
                $res["token"] = $this->token;
                $res["result"] = true;
                $res["boutique_name"] = $result["boutiqueName"];
                $res["ste_code"] = $result["ste_code"];
                $res["adress"] = $result["adress"];
                $res["zipCode"] = $result["zipCode"];
                $res["city"] = $result["city"];
                $res["enterprisePhone"] = $result["phoneNumber"];
                http_response_code(200);
                return $res;
            }
            return $this->setResponse(false, "Identifiant ou mot de passe incorrect", 400);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");

            http_response_code(500);
        }
    }

    private function verifyPasswordIsOk($passwordHashBdd, $password): bool
    {
        return password_verify($password, $passwordHashBdd);
    }

    protected function createToken($id, $boutique_id)
    {
        $token = new JWTController();
        $this->token = $token->createNewToken($id, time(), strtotime("+6 month"), ["boutique_id" => $boutique_id]);
    }
}
