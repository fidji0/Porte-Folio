<?php

namespace App\Class;

use App\Controller\CommonController;

class EmployeClass extends CommonController
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $surname;
    /**
     * @var string
     */
    protected $phone;
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $notif_phone_id;
    /**
     * @var string
     */
    protected $password;

    /**
     * @var int
     */
    protected $boutique_id;
    /**
     * @var float
     */
    protected $contrat;
    /**
     * @var int
     */
    protected $solde_conges;
    /**
     * @var string
     */
    protected $color;





    // create
    public function createNewEmploye()
    {
        if (
            empty($this->name) ||
            empty($this->surname) ||
            empty($this->email) ||
            empty($this->phone) ||
            empty($this->boutique_id) ||
            empty($this->contrat) ||
            empty($this->color) ||
            empty($this->password)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            if ($this->verifyUserExist()) {
                return $this->setResponse(false, "Email déja utilisé", 500);
            }
            $hashPassword = password_hash($this->password, PASSWORD_BCRYPT);
            $request = "INSERT INTO employe (name , surname , email , phone , boutique_id , contrat , password , color)
            VALUES (? , ? , ? , ? , ? , ? , ? , ? ) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->name, $this->surname, $this->email, $this->phone, $this->boutique_id, $this->contrat, $hashPassword, $this->color]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            return $this->readEmploye();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "class");
            return false;
        }
    }


    //Update
    public function updateEmploye()
    {
        if (
            empty($this->name) ||
            empty($this->id) ||
            empty($this->surname) ||
            empty($this->email) ||
            empty($this->phone) ||
            empty($this->boutique_id) ||
            empty($this->color) ||
            empty($this->contrat)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "UPDATE employe SET name = ? , surname = ? , email = ? , phone = ? , contrat = ? , color = ? ";
            if (!empty($this->password)) {
                $hash = password_hash($this->password, PASSWORD_BCRYPT);
                $request .= ", password = '$hash' ";
            }
            $request .= " WHERE id = ? AND boutique_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->name, $this->surname, $this->email, $this->phone, $this->contrat, $this->color, $this->id, $this->boutique_id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Update Employe : " . $th, "class");
            return false;
        }
    }


    // Read
    public function readEmploye()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)

        ) {
            return $this->setResponse(false, "Id Manquant", 401);
        }
        try {
            $request = "SELECT emp.*, 
       JSON_OBJECT(
           'week_templates', 
           JSON_ARRAYAGG(
               JSON_OBJECT(
                   'id', wt.id,
                   'name', wt.name,
                   'week_template', wt.week_template
               )
           )
       ) AS week_templates,
       
       JSON_OBJECT(
           'skills', 
           JSON_ARRAYAGG(
               DISTINCT JSON_OBJECT(
                   'id', s.id,
                   'name', s.name
               )
           )
       ) AS skills
       
FROM employe emp
LEFT JOIN week_templates wt ON emp.id = wt.employe_id
LEFT JOIN empSkills es ON emp.id = es.employe_id
LEFT JOIN skills s ON es.skill_id = s.id
WHERE emp.id = ? AND emp.boutique_id = ?
GROUP BY emp.id;";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe : " . $th, "class");
            return false;
        }
    }

    public function readAllEmployeBoutique()
    {
        if (
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT emp.*, 
       JSON_OBJECT(
           'week_templates', 
           JSON_ARRAYAGG(
               JSON_OBJECT(
                   'id', wt.id,
                   'name', wt.name,
                   'week_template', wt.week_template
               )
           )
       ) AS week_templates,
       
       JSON_OBJECT(
           'skills', 
           JSON_ARRAYAGG(
               DISTINCT JSON_OBJECT(
                   'id', s.id,
                   'name', s.name
               )
           )
       ) AS skills
       
FROM employe emp
LEFT JOIN week_templates wt ON emp.id = wt.employe_id
LEFT JOIN empSkills es ON emp.id = es.employe_id
LEFT JOIN skills s ON es.skill_id = s.id
WHERE emp.boutique_id = ?
GROUP BY emp.id;";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe With BoutiqueId : " . $th, "class");
            return false;
        }
    }

    // Delete
    public function deleteEmploye()
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM employe WHERE id = ? AND boutique_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Employe : " . $th, "class");
            return false;
        }
    }
    public function updateNotifId()
    {
        if (
            empty($this->email) ||
            empty($this->notif_phone_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "UPDATE employe SET notif_phone_id = ? WHERE email = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->notif_phone_id, $this->email]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Update Notif Employe : " . $th, "class");
            return false;
        }
    }

    protected function verifyUserExist(): bool
    {
        if (
            empty($this->email)
        ) {
            try {
                $request = "SELECT * FROM employe WHERE email = ?";
                $r = $this->pdo->prepare($request);
                $r->execute([$this->email]);
                if ($res = $r->fetchAll(\PDO::FETCH_ASSOC) == false) {
                    return false;
                }
                return true;
            } catch (\Throwable $th) {
                $this->logError(date("d-m-Y H:i:s") . " Read Employe With BoutiqueId : " . $th, "class");
                return false;
            }
        }
        return false;
    }




    /**
     * Get the value of password
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of color
     *
     * @return  string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @param  string  $color
     *
     * @return  self
     */
    public function setColor(string $color)
    {
        $this->color = $color;

        return $this;
    }
    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     *
     * @return  string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @param  string  $surname
     *
     * @return  self
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of phone
     *
     * @return  string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param  string  $phone
     *
     * @return  self
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Get the value of boutique_id
     *
     * @return  int
     */
    public function getBoutique_id()
    {
        return $this->boutique_id;
    }

    /**
     * Set the value of boutique_id
     *
     * @param  int  $boutique_id
     *
     * @return  self
     */
    public function setBoutique_id(int $boutique_id)
    {
        $this->boutique_id = $boutique_id;

        return $this;
    }

    /**
     * Get the value of contrat
     *
     * @return  float
     */
    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * Set the value of contrat
     *
     * @param  int  $contrat
     *
     * @return  self
     */
    public function setContrat(float $contrat)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get the value of solde_conges
     *
     * @return  int
     */
    public function getSolde_conges()
    {
        return $this->solde_conges;
    }

    /**
     * Set the value of solde_conges
     *
     * @param  int  $solde_conges
     *
     * @return  self
     */
    public function setSolde_conges(int $solde_conges)
    {
        $this->solde_conges = $solde_conges;

        return $this;
    }
    /**
     * Get the value of notif_phone_id
     *
     * @return  string
     */
    public function getNotif_phone_id()
    {
        return $this->notif_phone_id;
    }

    /**
     * Set the value of notif_phone_id
     *
     * @param  string  $notif_phone_id
     *
     * @return  self
     */
    public function setNotif_phone_id(string $notif_phone_id)
    {
        $this->notif_phone_id = $notif_phone_id;

        return $this;
    }
}
