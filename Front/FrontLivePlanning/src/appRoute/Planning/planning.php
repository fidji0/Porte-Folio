<?php

use App\Controller\Boutique;
use App\Controller\Employe;
use App\Controller\Event;
use App\Controller\TaskSkill;

if ($connected === false) {
    echo '<script>window.location.href = "login";</script>';
    exit();
}
try {
    $taskOption = false;
    $skillsOption = false;
    $taskSkill = new TaskSkill();
    if (isset($_SESSION['options']) && in_array('tasks', $_SESSION['options'])) {
        $taskOption = true;
    }
    if (isset($_SESSION['options']) && in_array('skills', $_SESSION['options'])) {
        $skillsOption = true;
    }

    $event = new Event();
    $employe = new Employe();
    $emp = $employe->readEmploye(); // array
    $ev = $event->readEvent(); //string
    $tasks = $taskSkill->readTask(); //string
    $skills = $taskSkill->readSkill(); // string


    $allEmployeeStats = $employe->calculateAllEmployeeStats(json_decode($ev, true), $emp);


    if (isset($_POST["allRefresh"])) {
        $val = ["stats" => $allEmployeeStats, "events" => json_decode($ev, true), "emp" => $emp, "tasks" => json_decode($tasks, true)];

        echo json_encode($val);
        exit;
    }


    //cas readSuite créate ou modif
    if (isset($_POST["maj"])) {
        echo $ev;
        exit;
    }

    //cas mise à jour d'un event
    try {
        if (isset(
            $_POST["id"],
            $_POST['update'],
            $_POST["employe_id"],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['type']
        )) {
            $res = $event->updateEvent(
                $_POST['id'],
                $_POST["employe_id"],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['objet']?? null,
                $_POST['lieu'] ?? null,
                $_POST['type'],
                $_POST['detail'] ?? null,
                $_POST['equivWorkTime'] ?? null

            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(200);
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    }


    //cas envoie feuille d'heure
    try {
        if (isset(
            $_POST['week_number'],
            $_POST["employe_id"]
        )) {
            $res = $event->updateEvent(
                $_POST['id'],
                $_POST["employe_id"],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['objet']?? null,
                $_POST['lieu'] ?? null,
                $_POST['type'],
                $_POST['detail'] ?? null,
                $_POST['equivWorkTime'] ?? null

            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(200);
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    }
    //cas création d'un event
    try {
        if (isset(
            $_POST['create'],
            $_POST['employe_id'],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['type']
        )) {
            $res = $event->createEvent(
                $_POST['employe_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['objet'] ?? null,
                $_POST['lieu'] ?? null,
                $_POST['type'],
                $_POST['detail'] ?? null,
                $_POST['equivWorkTime'] ?? null


            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(201);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    //cas création d'une tache
    try {
        if (isset(
            $_POST['createTask'],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['objet'],
        ) && $taskOption) {
            $res = $taskSkill->createTask(
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['objet'],
                $_POST['lieu'] ?? null,
                $_POST['minPerson'] ?? null,
                $_POST['maxPerson'] ?? null,
                $_POST['event_type'] ?? null

            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(201);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    //cas update d'une tache
    try {
        if (isset(
            $_POST['updateTask'],
            $_POST['id'],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['objet'],
        ) && $taskOption) {
            $res = $taskSkill->updateTask(
                $_POST['id'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['objet'],
                $_POST['lieu'] ?? null,
                $_POST['minPerson'] ?? null,
                $_POST['maxPerson'] ?? null,
                $_POST['type'] ?? null

            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(201);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }



    //cas création d'un model de semaine
    try {
        if (isset(
            $_POST['save_week'],
            $_POST['week_template'],
            $_POST['employe_id']
        )) {
            $res = $event->createWeekModel(

                $_POST['week_template'],
                $_POST['employe_id'],
                $_POST['name'] ?? "test ",

            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(201);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }

    //cas suppression d'un event
    if (isset(
        $_POST['delete'],
        $_POST['id']
    )) {
        $res = $event->deleteEvent(
            $_POST['id']
        );

        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }
    //cas suppression d'une tache
    if (isset(
        $_POST['deleteTask'],
        $_POST['id']
    ) && $taskOption) {
        $res = $taskSkill->deleteTask(
            $_POST['id']
        );

        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }
    //cas suppression d'un event
    if (isset(
        $_POST['deleteWeek'],
        $_POST['start_date']
    )) {
        $res = $event->deleteWeekEvent(
            $_POST['start_date'],
            $_POST['employe_id'] ?? null
        );

        if ($res == false) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }
    //cas validation d'un event d'un event
    try {
        if (isset(
            $_POST["id"],
            $_POST['validate']
        )) {
            $res = $event->validateEvent(
                $_POST['id']
            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(200);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    //cas validation d'une semaine event
    try {
        if (isset(
            $_POST["start_date"],
            $_POST['validateWeek']
        )) {
            $res = $event->validateWeekEvent(
                $_POST["start_date"],
                $_POST['employe_id'] ?? null
            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(200);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    //cas association skill task
    try {
        if (isset(
            $_POST['skillTask'],
            $_POST['task_id'],
            $_POST['skill_id']
        )) {
            $res = $taskSkill->createTaskSkill(
                $_POST['task_id'],
                $_POST['skill_id']
            );
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(201);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    //cas delete skill task
    try {
        if (isset(
            $_POST['deleteTaskSkill'],
            $_POST['task_id'],
            $_POST['skill_id']
        )) {
            $res = $taskSkill->deleteTaskSkill(
                $_POST['task_id'],
                $_POST['skill_id']
            );
            
            if ($res == false) {
                http_response_code(400);
            } else {
                http_response_code(200);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }

    if (!isset($_POST) || empty($_POST)) {


        $bout = $_SESSION["boutique"];

        include_once DIRVUE . "/template/Planning/planning.php";
    }

} catch (\Throwable $th) {
    file_put_contents(DIR . "/error/fichier.log", date("d-m-Y H:i:s") .  $th . "\n", FILE_APPEND);
}
