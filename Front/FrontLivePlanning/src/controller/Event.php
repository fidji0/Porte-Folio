<?php

namespace App\Controller;

class Event extends Curl
{

    public function readEvent()
    {
        $headers = array(
            'Content-Type: application/json',
            "Authorization: " . $_SESSION['token'],
        );
        $this->commonCurlGet(URLEVENT . "/read?boutique_id=" . $_SESSION["idBoutique"], $headers, 200);

        return ($this->returnServer);
    }

    public function createEvent(
        $employe_id,
        $start_date,
        $end_date,
        $objet,
        $lieu,
        $type,
        $detail,
        $equivWorkTime
    ) { {

            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'employe_id' => $employe_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'objet' => $objet,
                'lieu' => $lieu,
                'detail' => $detail,
                'type' => $type,
                'equivWorkTime' => $equivWorkTime,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLEVENT . "/create", 201, $headers);
        }
    }

    public function createWeekModel(
        
        $week_event,
        $employe_id,
        $name = "noname"
    ) { 
        

            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'employe_id' => $employe_id,
                'week_template' => $week_event,
                'name' => $name,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLEVENT . "/createWeek", 201, $headers);
        
    }


    public function updateEvent(
        $id,
        $employe_id,
        $start_date,
        $end_date,
        $objet,
        $lieu,
        $type,
        $detail = null,
        $equivWorkTime = null
    ) { 

            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                "id" => $id,
                'employe_id' => $employe_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'objet' => $objet ?? null,
                'lieu' => $lieu?? null,
                'detail' => $detail,
                'type' => $type,
                'boutique_id' => $_SESSION["idBoutique"],
                'equivWorkTime' => $equivWorkTime
            ];

            return $this->commonCurlPOST($data, URLEVENT . "/update", 200, $headers);
        
    }

    public function createTimeSheet(
        
        $employe_id,
        $week_number
    ) { 

            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                
                'employe_id' => $employe_id,
                'week_number' => $week_number,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLEVENT . "/timeSheetCreate", 201, $headers);
        
    }

    public function validateEvent(
        $id
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                "id" => $id,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLEVENT . "/validate", 200, $headers);
        }
    }


    public function validateWeekEvent(
        $start_date , $employe_id = null
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                "start_date" => $start_date,
                'boutique_id' => $_SESSION["idBoutique"]
            ];
            if (!is_null($employe_id)) {
                $data['employe_id'] = $employe_id;
            }
            return $this->commonCurlPOST($data, URLEVENT . "/validateWeek", 200, $headers);
        }
    }
    public function deleteEvent($id)
    { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'id' => $id,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlDelete(URLEVENT . "/delete?id=$id&boutique_id=" . $_SESSION["idBoutique"], 200, $headers);
        }
    }
    public function deleteWeekEvent($start_date , $employe_id = null)
    { {
        
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'start_date' => $start_date,
                'boutique_id' => $_SESSION["idBoutique"]
            ];
            $url = "/deleteWeek?start_date=$start_date&boutique_id=" . $_SESSION["idBoutique"];
            if (!empty($employe_id)) {
               $url .= "&employe_id=$employe_id" ;
            }
            return $this->commonCurlDelete(URLEVENT . $url, 200, $headers);
        }
    }
    
}
