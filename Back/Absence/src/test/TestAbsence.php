<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestAbsence extends TestCase
{
    private $client;
    protected $validToken;
    protected $validTokenEmploye;
    protected static $absenceId;

    protected function setUp(): void

    {
        define("ENV", "dev");
        define("DIR", __DIR__);
        define("USER", "");
        define("PASSWORD", "");
        define("DIRMAIL", __DIR__ . "/src/templateEmail");
        $other = [
            "email" => "a.vincent.anthony@gmail.com",
            "id" => 6,
            "role" => "user"
        ];
        $token = new JWTController();
        $this->validToken = $token->createNewToken("6", time(), time() + 3600, $other);
        $this->validTokenEmploye = $token->createNewToken("8", time(), time() + 3600);
        //echo $this->validToken;
        $this->client = new Client([
            'base_uri' => 'http://localhost:9000/', // Remplacez par l'URL de base de votre API
        ]);
        
    }



    public function testSuccessfulCreateAbsence()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 8,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'boutique_id' => 6,
                'type' => "maladie",
                "objet" => "maladie"
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$absenceId = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(8, $responseBody[0]["employe_id"]);
        
    }

    public function testCreateAbsenceWithMissingData()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 8,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'boutique_id' => 6,
                "objet" => "maladie"
            
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedAccess()
    {
        global $authenticate;
        $authenticate = false;

        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer invalidtojddf", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 8,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'boutique_id' => 6,
                'type' => "maladie",
                "objet" => "maladie"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testCreateAbsenceWithInvalidEmploye()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 9,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'boutique_id' => 6,
                'type' => "maladie",
                "objet" => "maladie"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(500, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('erreur lors de la création', $responseBody['message']);
    }


    //Read employe
    public function testSuccessfulReadAbsence()
    {
        $response = $this->client->get('read?id=11&boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('employe_id', $responseBody[0]);
        $this->assertEquals(8, $responseBody[0]['employe_id']);
    }

    public function testReadAbsneceWithInvalidToken()
    {
        $response = $this->client->get('read', [
            'headers' => [
                'Authorization' => 'Bearer invalid_token',
            ],
            'form_params' => [
                'id' => 1,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testReadAbsenceWithMissingData()
    {
        $response = $this->client->get('read', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testReadAbsenceWithoutToken()
    {
        $response = $this->client->get('read', [
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }



    //Read All with boutique id
    public function testSuccessfulReadAllAbsence()
    {
        $response = $this->client->get('readAll?boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('employe_id', $responseBody[0]);
        $this->assertEquals(8, $responseBody[0]['employe_id']);
    }

    public function testReadAllAbsenceWithInvalidToken()
    {
        $response = $this->client->get('readAll', [
            'headers' => [
                'Authorization' => 'Bearer invalid_token',
            ],
            'query' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testReadAllAbsenceWithMissingData()
    {
        $response = $this->client->get('readAll', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testReadAllAbsenceWithoutToken()
    {
        $response = $this->client->get('readAll', [
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }




    // test mise a jour employe 
    public function testSuccessfulUpdateAbsence()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> 2,
                'employe_id' => 8,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'boutique_id' => 6,
                'type' => 'maladie',
                'validate' => 1,
                "objet" => "maladie"
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Mis à jour avec succès', $responseBody['message']);
    }

    public function testUpdateEmployeWithMissingData()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id' => 1,
                'name' => 'Jane',

            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedUpdateEmploye()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => 'InvalidToken',
            ],
            'form_params' => [
                'id'=> 2,
                'employe_id' => 8,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'boutique_id' => 6,
                'type' => 'maladie',
                'validate' => 1,
                "objet" => "maladie"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

   

    // test delete employe
    public function testSuccessfulDeleteEmploye()
    {
        $response = $this->client->delete('delete', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'id' => self::$absenceId,
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

    public function testDeleteEmployeWithMissingData()
    {
        $response = $this->client->delete('delete', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                // 'id' is missing here
                'boutique_id' => 2,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedDeleteEmploye()
    {
        $response = $this->client->delete('delete', [
            'headers' => [
                'Authorization' => 'InvalidToken',
            ],
            'query' => [
                'id' => 1,
                'boutique_id' => 2,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
