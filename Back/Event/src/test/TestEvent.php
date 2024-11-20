<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestEvent extends TestCase
{
    private $client;
    protected $validToken;
    protected $validTokenEmploye;
    protected static $eventId;

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
        $this->client = new Client([
            'base_uri' => 'http://localhost:9030/', // Remplacez par l'URL de base de votre API
        ]);
        echo $this->validToken;
        $this->validTokenEmploye = $token->createNewToken("8", time(), time() + 3600 , ["boutique_id" => 6]);
        
        
    }



    public function testSuccessfulCreateEvent()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 8,
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "ras",
                'lieu' => "sur site",
                'type' => 'TRAVAIL',
                'equivWorkTime' => 8
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$eventId = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(8, $responseBody[0]["employe_id"]);
        
    }
    public function testSuccessful2CreateEvent()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 11,
                'boutique_id' => 6,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'objet' => 'matinée',
                'detail' => "ras",
                'lieu' => "sur site",
                'type' => 'TRAVAIL'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(11, $responseBody[0]["employe_id"]);
        
    }
    public function testErrorCreateEventWithOtherEmploye()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 32,
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "ras",
                'lieu' => "sur site",
                'type' => 'TRAVAIL'
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        
        $this->assertArrayHasKey('result', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals('Num employe non valide', $responseBody['message']);
        
    }
    public function testCreateEventWithMissingData()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 8,
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",



                'type' => 'TRAVAIL'
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
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "ras",
                'lieu' => "sur site",
                'type' => 'TRAVAIL'
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

   


    //Read event
    public function testSuccessfulReadEvent()
    {
        $response = $this->client->get('read?boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('employe_id', $responseBody[0]);
        $this->assertArrayHasKey('name', $responseBody[0]);
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

    public function testReadEventWithMissingData()
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
        $this->assertEquals('Id manquant ou invalide', $responseBody['message']);
    }

    public function testReadEventWithoutToken()
    {
        $response = $this->client->get('read', [
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }



    // test mise a jour event 
    
    public function testErrorUpdateEventNoElementMaj()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> self::$eventId,
                'employe_id' => 8,
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "ras",
                'lieu' => "sur site",
                'type' => 'TRAVAIL',
                'equivWorkTime' => 8
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Aucun élément à mettre à jour', $responseBody['message']);
    }
    
    public function testSuccessfulUpdateEvent()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> self::$eventId,
                'employe_id' => 8,
                'boutique_id' => 6,
                'start_date' => "2024-08-22T14:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "test",
                'lieu' => "sur site",
                'type' => 'TRAVAIL',
                'equivWorkTime' => 6
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Mis à jour avec succès', $responseBody['message']);
    }
    public function testSuccessfulValidateWeekEvent()
    {
        $response = $this->client->post('validateWeek', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'start_date' => "2024-08-22T15:12",
                'boutique_id' => 6,
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Activé avec succès', $responseBody['message']);
    }

   

    public function testErrorUpdateEventWithBadEmploye()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> 20,
                'employe_id' => 36,
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "test",
                'lieu' => "sur site",
                'type' => 'TRAVAIL'
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(403, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Num employe non valide', $responseBody['message']);
    }

    public function testUpdateeventWithMissingData()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id' => 2,
                'start_date' => 'Jane',

            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedUpdateevent()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => 'InvalidToken',
            ],
            'form_params' => [
                'id'=> 2,
                'employe_id' => 8,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'boutique_id' => 6,
                'type' => 'maladie',
                'validate' => 1,
                'type' => 'TRAVAIL'
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testSuccessfulValidateEvent()
    {
        $response = $this->client->post('validate', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> self::$eventId,
                'boutique_id' => 6,
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Activé avec succès', $responseBody['message']);
    }

    // test delete event
    public function testSuccessfulDeleteevent()
    {
        $response = $this->client->delete('delete', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'id' => self::$eventId,
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

    public function testDeleteeventWithMissingData()
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
        $this->assertEquals('Id manquant ou invalide', $responseBody['message']);
    }

    public function testUnauthorizedDeleteevent()
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
    // test delete event
    public function testSuccessfulDeleteWeekEvent()
    {
        $response = $this->client->delete('deleteWeek', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'start_date' => "2024-08-23T15:12",
                'employe_id' => 11,
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

}
