<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestAbsenceEmploye extends TestCase
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
        
        $token = new JWTController();
        $this->validToken = $token->createNewToken("8", time(), time() + 3600);

        $this->client = new Client([
            'base_uri' => 'http://localhost:9000/', // Remplacez par l'URL de base de votre API
        ]);
        
    }



    public function testSuccessfulCreateAbsence()
    {
        $response = $this->client->post('user_create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'start_date' => "2024-08-24T15:13",
                'end_date' => "2024-08-24T19:12",
                'type' => "congès",
                "objet" => "maladie"
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$absenceId = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(8, $responseBody[0]["employe_id"]);
        
    }
    public function testErrorCreateAbsenceAlreadyExist()
    {
        $response = $this->client->post('user_create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'start_date' => "2024-08-24T15:13",
                'end_date' => "2024-08-24T19:12",
                'type' => "congès",
                "objet" => "maladie"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('La demande existe déja', $responseBody['message']);
        
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

    


    //Read All with user id
    public function testSuccessfulReadAllAbsence()
    {
        $response = $this->client->get('readAllAbsence', [
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
        $response = $this->client->get('readAllAbsence', [
            'headers' => [
                'Authorization' => 'Bearer invalid_token',
            ],
            'query' => [
                
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }


    public function testReadAllAbsenceWithoutToken()
    {
        $response = $this->client->get('readAllAbsence', [
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
        $response = $this->client->post('updateAbsenceEmploye', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> 2,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'type' => 'maladie',
                "objet" => "maladie"
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Mis à jour avec succès', $responseBody['message']);
    }

    public function testUpdateEmployeWithMissingData()
    {
        $response = $this->client->post('updateAbsenceEmploye', [
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
        $response = $this->client->post('updateAbsenceEmploye', [
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
        $response = $this->client->delete('deleteAbsenceEmploye', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'id' => self::$absenceId ,
                
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

    public function testDeleteEmployeWithMissingData()
    {
        $response = $this->client->delete('deleteAbsenceEmploye', [
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
        $response = $this->client->delete('deleteAbsenceEmploye', [
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
