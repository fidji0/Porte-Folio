<?php

namespace App\Controller;

class TaskSkill extends Curl
{

    public function readSkill()
    {
        $headers = array(
            'Content-Type: application/json',
            "Authorization: " . $_SESSION['token'],
        );
        $this->commonCurlGet(URLTASK . "/readSkill?boutique_id=" . $_SESSION["idBoutique"], $headers, 200);

        return ($this->returnServer);
    }

    public function createSkill(
        $name
    ) { {

            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'name' => $name,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLTASK . "/createSkill", 201, $headers);
        }
    }


    public function updateSkill(
        $id,
        $name
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                "id" => $id,
                'name' => $name,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLTASK . "/updateSkill", 200, $headers);
        }
    }

    
    public function deleteSkill($id)
    { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            

            return $this->commonCurlDelete(URLTASK . "/deleteSkill?id=$id&boutique_id=" . $_SESSION["idBoutique"], 200, $headers);
        }
    }
   
    public function readTask()
    {
        $headers = array(
            'Content-Type: application/json',
            "Authorization: " . $_SESSION['token'],
        );
        $this->commonCurlGet(URLTASK . "/readTask?boutique_id=" . $_SESSION["idBoutique"], $headers, 200);

        return ($this->returnServer);
    }

    public function createTask(

        $start_date,
        $end_date,
        $objet,
        $lieu = null,
        $minPerson = null,
        $maxPerson = null,
        $eventType = null
    ) { {

            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'objet' => $objet,
                'lieu' => $lieu,
                'minPerson' => $minPerson,
                'maxPerson' => $maxPerson,
                'eventType' => $eventType,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLTASK . "/createTask", 201, $headers);
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


    public function updateTask(
        $id,
        $start_date,
        $end_date,
        $objet,
        $lieu = null,
        $minPerson = null,
        $maxPerson = null,
        $eventType = null
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                "id" => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'objet' => $objet,
                'lieu' => $lieu,
                'minPerson' => $minPerson,
                'maxPerson' => $maxPerson,
                'eventType' => $eventType,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLTASK . "/updateTask", 200, $headers);
        }
    }

    

    
    public function deleteTask($id)
    { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            $data = [
                'id' => $id,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlDelete(URLTASK . "/deleteTask?id=$id&boutique_id=" . $_SESSION["idBoutique"], 200, $headers);
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
    public function createEmpSkill(
        $employe_id,
        $skill_id,
        
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded', // Exemple d'en-tête JSON
                "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
            );
            $data = [
                'employe_id' => $employe_id,
                'skill_id' => $skill_id,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLTASK . "/createEmpSkill", 201, $headers);
        }
    }
    public function deleteSkillEmp($id , $skill_id)
    { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            

            return $this->commonCurlDelete(URLTASK . "/deleteEmpSkill?employe_id=$id&skill_id=$skill_id&boutique_id=" . $_SESSION["idBoutique"], 200, $headers);
        }
    }



    public function createTaskSkill(
        $task_id,
        $skill_id,
        
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded', // Exemple d'en-tête JSON
                "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
            );
            $data = [
                'task_id' => $task_id,
                'skill_id' => $skill_id,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLTASK . "/createTaskSkill", 201, $headers);
        }
    }
    public function deleteTaskSkill($task_id , $skill_id)
    { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: " . $_SESSION['token'],
            );
            

            return $this->commonCurlDelete(URLTASK . "/deleteTaskSkill?task_id=$task_id&skill_id=$skill_id&boutique_id=" . $_SESSION["idBoutique"], 200, $headers);
        }
    }

}
