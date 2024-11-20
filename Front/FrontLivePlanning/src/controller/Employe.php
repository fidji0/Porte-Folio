<?php

namespace App\Controller;

use DateTime;

class Employe extends Curl
{

    public function readEmploye()
    {
        $headers = array(
            'Content-Type: application/json', // Exemple d'en-tête JSON
            "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
        );
        $this->commonCurlGet(URLEMPLOYE . "/readAll?boutique_id=" . $_SESSION["idBoutique"], $headers, 200);

        return (json_decode($this->returnServer, true));
    }

    public function createEmploye(
        string $name,
        string $surname,
        $email,
        $password,
        $phone,
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
                'contrat' => $contrat,
                'color' => $color,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlPOST($data, URLEMPLOYE . "/create", 201, $headers);
        }
    }


    public function updateEmploye(
        $id,
        string $name,
        string $surname,
        $email,
        $phone,
        $contrat,
        $color,
        $password = null
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded', // Exemple d'en-tête JSON
                "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
            );
            $data = [
                "id" => $id,
                'name' => $name,
                'surname' => $surname,
                'phone' => $phone,
                'email' => $email,
                'contrat' => $contrat,
                'color' => $color,
                'boutique_id' => $_SESSION["idBoutique"]
            ];
            if (isset($password) && !empty($password)) {
                $data["password"] = $password;
            }

            return $this->commonCurlPOST($data, URLEMPLOYE . "/update", 200, $headers);
        }
    }

    public function deleteEmploye(
        $id
    ) { {
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded', // Exemple d'en-tête JSON
                "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
            );
            $data = [
                'id' => $id,
                'boutique_id' => $_SESSION["idBoutique"]
            ];

            return $this->commonCurlDelete(URLEMPLOYE . "/delete?id=$id&boutique_id=" . $_SESSION["idBoutique"], 200, $headers);
        }
    }

    public function readEvents()
    {
        $headers = array(
            'Content-Type: application/json', // Exemple d'en-tête JSON
            "Authorization: " . $_SESSION['token'], // Exemple d'en-tête d'autorisation
        );
        $this->commonCurlGet(URLEVENT . "/read?boutique_id=" . $_SESSION["idBoutique"], $headers, 200);

        return (json_decode($this->returnServer, true));
    }


    function calculateAllEmployeeStats($events, $employees)
{
    if (empty($employees) || isset($events["result"])) {
        return [];
    }

    $stats = [];

    // Initialiser les statistiques pour chaque employé
    foreach ($employees as $employee) {
        $stats[$employee['id']] = [
            'name' => $employee['name'] . ' ' . $employee['surname'],
            'hours' => $employee['contrat'],
            "id" => $employee["id"],
            'daily' => [],
            'weekly' => [],
            'monthly' => []
        ];

        foreach (array_keys(EVENT_TYPES) as $type) {
            $stats[$employee['id']]['daily'][$type] = [];
            $stats[$employee['id']]['weekly'][$type] = [];
            $stats[$employee['id']]['monthly'][$type] = [];
        }
    }

    // Calculer les statistiques pour tous les événements
    foreach ($events as $event) {
        $employeeId = $event['employe_id'];
        $type = array_key_exists($event['type'], EVENT_TYPES) ? $event['type'] : 'AUTRE';

        $start = new DateTime($event['start_date']);
        $end = new DateTime($event['end_date']);
        $duration = ($end->getTimestamp() - $start->getTimestamp()) / 3600; // Durée en heures

        // Si le type n'est pas "TRAVAIL", ajouter equivWorkTime au temps de travail
        if ($type !== 'TRAVAIL' && isset($event['equivWorkTime'])) {
            $workType = 'TRAVAIL';

            // Statistiques journalières
            $day = $start->format('Y-m-d');
            if (!isset($stats[$employeeId]['daily'][$workType][$day])) {
                $stats[$employeeId]['daily'][$workType][$day] = 0;
            }
            $stats[$employeeId]['daily'][$workType][$day] += $event['equivWorkTime'];

            // Statistiques hebdomadaires
            $year = $start->format('o');  // Année ISO
            $week = $start->format('W');  // Semaine ISO
            $weekKey = $year . '-' . $week;

            if (!isset($stats[$employeeId]['weekly'][$workType][$weekKey])) {
                $stats[$employeeId]['weekly'][$workType][$weekKey] = 0;
            }
            $stats[$employeeId]['weekly'][$workType][$weekKey] += $event['equivWorkTime'];

            // Statistiques mensuelles
            $month = $start->format('Y-m');
            if (!isset($stats[$employeeId]['monthly'][$workType][$month])) {
                $stats[$employeeId]['monthly'][$workType][$month] = 0;
            }
            $stats[$employeeId]['monthly'][$workType][$month] += $event['equivWorkTime'];
        }

        // Ajouter la durée pour le type d'événement actuel
        // Statistiques journalières
        $day = $start->format('Y-m-d');
        if (!isset($stats[$employeeId]['daily'][$type][$day])) {
            $stats[$employeeId]['daily'][$type][$day] = 0;
        }
        $stats[$employeeId]['daily'][$type][$day] += $duration;

        // Statistiques hebdomadaires
        $year = $start->format('o');  // Année ISO
        $week = $start->format('W');  // Semaine ISO
        $weekKey = $year . '-' . $week;

        if (!isset($stats[$employeeId]['weekly'][$type][$weekKey])) {
            $stats[$employeeId]['weekly'][$type][$weekKey] = 0;
        }
        $stats[$employeeId]['weekly'][$type][$weekKey] += $duration;

        // Statistiques mensuelles
        $month = $start->format('Y-m');
        if (!isset($stats[$employeeId]['monthly'][$type][$month])) {
            $stats[$employeeId]['monthly'][$type][$month] = 0;
        }
        $stats[$employeeId]['monthly'][$type][$month] += $duration;
    }

    // Trier les données par ordre chronologique pour chaque employé et type d'événement
    foreach ($stats as &$employeeStats) {
        foreach (array_keys(EVENT_TYPES) as $type) {
            ksort($employeeStats['daily'][$type]);
            ksort($employeeStats['weekly'][$type]);
            ksort($employeeStats['monthly'][$type]);
        }
    }

    return $stats;
}



    function calculateAllEmployeeStatsWithoutInactive($events, $employees)
    {
        if (empty($employees) || isset($events["result"])) {
            return [];
        }
        $stats = [];

        // Initialiser les statistiques pour chaque employé
        foreach ($employees as $employee) {
            $stats[$employee['id']] = [
                'name' => $employee['name'] . ' ' . $employee['surname'],
                'hours' => $employee['contrat'],
                "id" => $employee["id"],
                'daily' => [],
                'weekly' => [],
                'monthly' => []
            ];

            foreach (array_keys(EVENT_TYPES) as $type) {
                $stats[$employee['id']]['daily'][$type] = [];
                $stats[$employee['id']]['weekly'][$type] = [];
                $stats[$employee['id']]['monthly'][$type] = [];
            }
        }

        // Calculer les statistiques pour tous les événements
        foreach ($events as $event) {
            if ($event["validate"] == 1) {
                $employeeId = $event['employe_id'];
                $type = array_key_exists($event['type'], EVENT_TYPES) ? $event['type'] : 'AUTRE';

                $start = new DateTime($event['start_date']);
                $end = new DateTime($event['end_date']);
                $duration = ($end->getTimestamp() - $start->getTimestamp()) / 3600; // Durée en heures

                // Cas spécifique pour Congés ou Maladie : calculer en jours
                if ($type == 'CONGES' || $type == 'MALADIE') {
                    // Calcul du nombre de jours (on arrondit au jour supérieur si des heures supplémentaires)
                    $daysDuration = $start->diff($end)->days + 1; // Ajout de +1 pour inclure le jour de départ
                    $reason = $event['objet'] ?? 'N/A'; // Récupérer la raison si disponible

                    // Statistiques journalières : Ne compter qu'un seul jour même s'il y a plusieurs événements
                    $day = $start->format('Y-m-d');
                    if (!isset($stats[$employeeId]['daily'][$type][$day])) {
                        $stats[$employeeId]['daily'][$type][$day] = ['days' => 1, 'reason' => $reason];  // Un seul jour compté
                    } else {
                        // Si un événement est déjà compté pour ce jour, ne pas le compter de nouveau
                        $stats[$employeeId]['daily'][$type][$day]['reason'] = $reason; // Met à jour la raison s'il y a un nouvel événement
                    }

                    // Statistiques hebdomadaires : Ne pas cumuler les événements d'un même jour
                    $week = $start->format('Y-W');
                    if (!isset($stats[$employeeId]['weekly'][$type][$week])) {
                        $stats[$employeeId]['weekly'][$type][$week] = ['days' => 1, 'reason' => $reason];
                    } else {
                        // Ne pas ajouter de nouveau si déjà compté, mais vérifier si un nouveau jour
                        $existingDays = $stats[$employeeId]['weekly'][$type][$week]['days'];
                        $stats[$employeeId]['weekly'][$type][$week]['days'] = max(1, $existingDays); // Max entre 1 et existant (pour éviter duplication)
                    }

                    // Statistiques mensuelles : Ne pas cumuler les événements d'un même jour
                    $month = $start->format('Y-m');
                    if (!isset($stats[$employeeId]['monthly'][$type][$month])) {
                        $stats[$employeeId]['monthly'][$type][$month] = ['days' => 1, 'reason' => $reason];
                    } else {
                        // Ne pas ajouter de nouveau si déjà compté
                        $existingDays = $stats[$employeeId]['monthly'][$type][$month]['days'];
                        $stats[$employeeId]['monthly'][$type][$month]['days'] = max(1, $existingDays); // Max entre 1 et existant
                    }
                } else {
                    // Autres types : Calcul en heures comme avant
                    // Statistiques journalières
                    $day = $start->format('Y-m-d');
                    if (!isset($stats[$employeeId]['daily'][$type][$day])) {
                        $stats[$employeeId]['daily'][$type][$day] = 0;
                    }
                    $stats[$employeeId]['daily'][$type][$day] += $duration;

                    // Statistiques hebdomadaires
                    $week = $start->format('Y-W');
                    if (!isset($stats[$employeeId]['weekly'][$type][$week])) {
                        $stats[$employeeId]['weekly'][$type][$week] = 0;
                    }
                    $stats[$employeeId]['weekly'][$type][$week] += $duration;

                    // Statistiques mensuelles
                    $month = $start->format('Y-m');
                    if (!isset($stats[$employeeId]['monthly'][$type][$month])) {
                        $stats[$employeeId]['monthly'][$type][$month] = 0;
                    }
                    $stats[$employeeId]['monthly'][$type][$month] += $duration;
                }
            }
        }

        // Trier les données par ordre chronologique pour chaque employé et type d'événement
        foreach ($stats as &$employeeStats) {
            foreach (array_keys(EVENT_TYPES) as $type) {
                ksort($employeeStats['daily'][$type]);
                ksort($employeeStats['weekly'][$type]);
                ksort($employeeStats['monthly'][$type]);
            }
        }

        return $stats;
    }
}
