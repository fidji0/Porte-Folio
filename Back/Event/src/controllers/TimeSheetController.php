<?php

namespace App\Controller;

use App\Class\timeSheetClass;
use DateTime;

class TimeSheetController extends CommonController
{

    public function createTimeSheet($data, $token)
    {

        try {
            if (
                empty($data["employe_id"]) ||
                empty($data["boutique_id"]) ||
                empty($data["week_number"])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }
            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($data['employe_id'], $data['boutique_id'])) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }
            $timeSheet = new timeSheetClass();
            $timeSheet->setEmploye_id($data['employe_id']);
            $timeSheet->setBoutique_id($data['boutique_id']);
            $timeSheet->setWeek_number($data['week_number']);
            return $timeSheet->createNewTimeSheet();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " create timesheet : " . $th, "controller");
            return false;
        }
    }

   
}
