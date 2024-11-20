<?php

namespace App\Class;

use App\Controller\CommonController;
use DateTime;

class timeSheetClass extends CommonController
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $email_send_code;
    /**
     * @var int
     */
    protected $signature_code;
    /**
     * @var dateTime
     */
    protected $email_send_date;
    /**
     * @var dateTime
     */
    protected $startDate;
    /**
     * @var dateTime
     */
    protected $endDate;
    /**
     * @var string
     */
    protected $week_work_sign;
    /**
     * @var int
     */
    protected $ste_validate;
    /**
     * @var int
     */
    protected $boutique_id;
    /**
     * @var int
     */
    protected $employe_id;
    /**
     * @var string
     */
    protected $week_number;


    //Create new timeSheet
    public function createNewTimeSheet()
    {
        if (
            empty($this->employe_id) ||
            empty($this->boutique_id) ||
            empty($this->week_number)
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        if (!$this->readStartEndWeek($this->week_number)) {
            return $this->setResponse(false, "Format semaine incorrect", 500);
        }
        if (!$this->readWeekWork()) {
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
        echo $this->week_work_sign;

        exit();
        try {
            $request = "INSERT INTO `timeSheet`( `week_work_sign`, `employe_id`, `boutique_id`, week_number) VALUES  ( ? , ? , ? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->week_work_sign, $this->employe_id, $this->boutique_id, $this->week_number]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);

            return $this->setResponse(true, "Créer avec succès", 201);;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
    }


    public function readWeekWork(): bool
    {

        if (
            empty($this->employe_id) ||
            empty($this->boutique_id) ||
            empty($this->endDate) ||
            empty($this->startDate)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "SELECT * FROM events WHERE employe_id = ? AND start_date > ? AND start_date < ?  AND validate = 1";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->startDate, $this->endDate]);
            $result = $r->fetchAll(\PDO::FETCH_ASSOC);
            $this->week_work_sign = json_encode($result);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return false;
        }
    }


    protected function readStartEndWeek($weekNumber): bool
    {
        if (!preg_match('/^\d{4}-(0[1-9]|[1-4][0-9]|5[0-3])$/', $weekNumber)) {
            // Le format est correct, donc on peut extraire l'année et le numéro de semaine

            $this->logError(date("d-m-Y H:i:s") . " Le format de semaine n'est pas conforme $weekNumber ", "class");
            return false;
        }
        // Séparer l'année et le numéro de semaine
        list($year, $week) = explode('-', $weekNumber);

        // Créer un objet DateTime pour le premier jour de la semaine
        $startOfWeek = new DateTime();
        $startOfWeek->setISODate($year, $week);
        $firstDay = $startOfWeek->format('Y-m-d');  // Premier jour de la semaine (lundi)

        // Calculer le dernier jour de la semaine (dimanche)
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');
        $lastDay = $endOfWeek->format('Y-m-d');  // Dernier jour de la semaine (dimanche)
        $this->startDate = $firstDay;
        $this->endDate = $lastDay;
        return true;
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
     * Get the value of email_send_code
     *
     * @return  int
     */
    public function getEmail_send_code()
    {
        return $this->email_send_code;
    }

    /**
     * Set the value of email_send_code
     *
     * @param  int  $email_send_code
     *
     * @return  self
     */
    public function setEmail_send_code(int $email_send_code)
    {
        $this->email_send_code = $email_send_code;

        return $this;
    }

    /**
     * Get the value of signature_code
     *
     * @return  int
     */
    public function getSignature_code()
    {
        return $this->signature_code;
    }

    /**
     * Set the value of signature_code
     *
     * @param  int  $signature_code
     *
     * @return  self
     */
    public function setSignature_code(int $signature_code)
    {
        $this->signature_code = $signature_code;

        return $this;
    }

    /**
     * Get the value of email_send_date
     *
     * @return  dateTime
     */
    public function getEmail_send_date()
    {
        return $this->email_send_date;
    }

    /**
     * Set the value of email_send_date
     *
     * @param  dateTime  $email_send_date
     *
     * @return  self
     */
    public function setEmail_send_date(DateTime $email_send_date)
    {
        $this->email_send_date = $email_send_date;

        return $this;
    }



    /**
     * Get the value of week_work_sign
     *
     * @return  string
     */
    public function getWeek_work_sign()
    {
        return $this->week_work_sign;
    }

    /**
     * Set the value of week_work_sign
     *
     * @param  string  $week_work_sign
     *
     * @return  self
     */
    public function setWeek_work_sign(string $week_work_sign)
    {
        $this->week_work_sign = $week_work_sign;

        return $this;
    }

    /**
     * Get the value of ste_validate
     *
     * @return  int
     */
    public function getSte_validate()
    {
        return $this->ste_validate;
    }

    /**
     * Set the value of ste_validate
     *
     * @param  int  $ste_validate
     *
     * @return  self
     */
    public function setSte_validate(int $ste_validate)
    {
        $this->ste_validate = $ste_validate;

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
     * Get the value of employe_id
     *
     * @return  int
     */
    public function getEmploye_id()
    {
        return $this->employe_id;
    }

    /**
     * Set the value of employe_id
     *
     * @param  int  $employe_id
     *
     * @return  self
     */
    public function setEmploye_id(int $employe_id)
    {
        $this->employe_id = $employe_id;

        return $this;
    }

    /**
     * Get the value of week_number
     *
     * @return  string
     */
    public function getWeek_number()
    {
        return $this->week_number;
    }

    /**
     * Set the value of week_number
     *
     * @param  string  $week_number
     *
     * @return  self
     */
    public function setWeek_number(string $week_number)
    {
        $this->week_number = $week_number;

        return $this;
    }
}
