<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestConnexion extends TestCase
{
    private $client;
    protected $validToken;
    protected static $user_id;

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
            'base_uri' => 'http://localhost:9020/', // Remplacez par l'URL de base de votre API
        ]);
        
    }



    public function testSuccessfulCreateEmploye()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'name' => 'John',
                'surname' => 'Doe',
                'password' => 'securepassword',
                'email' => 'johndoe@example.com',
                'phone' => '1234567890',
                'boutique_id' => 6,
                'contrat' => 35,
                'color' => "#D5DADF"
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$user_id = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        
    }

    public function testCreateEmployeWithMissingData()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'token' => 'validToken',
                'name' => 'John',
                'surname' => 'Doe',
                'password' => 'securepassword',
                'email' => 'johndoe@example.com',
                'boutique_id' => 11,
                'solde_conges' => 10,
                'contrat' => 35,
                'color' => "#D5DADF"
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
                'token' => 'invalidToken', // Simuler un accès non autorisé
                'name' => 'John',
                'surname' => 'Doe',
                'password' => 'securepassword',
                'email' => 'johndoe@example.com',
                'phone' => '1234567890',
                'boutique_id' => 11,
                'solde_conges' => 10,
                'contrat' => 35,
                'color' => "#D5DADF"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testCreateEmployeWithInvalidEmail()
    {
        $response = $this->client->post('create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'name' => 'John',
                'surname' => 'Doe',
                'password' => 'securepassword',
                'email' => 'invalid-email', // Email invalide
                'phone' => '1234567890',
                'boutique_id' => 6,
                'solde_conges' => 10,
                'contrat' => 35,
                'color' => "#D5DADF"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('email invalide', $responseBody['message']);
    }




    public function testSuccessfulConnexion()
    {
        $response = $this->client->post('connexion', [
            'form_params' => [
                'email' => 'johndoe@example.com',
                'password' => 'securepassword',
                'notif_phone_id' => 'device123',
                'sct_code' => 'test',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertArrayHasKey('token', $responseBody);
        $this->assertArrayHasKey('name', $responseBody);
        
    }

    public function testConnexionWithMissingEmail()
    {
        $response = $this->client->post('connexion', [
            'form_params' => [
                'password' => 'securepassword',
                'deviceId' => 'device123',
                'sct_code' => '12345',
            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Email ou mot de passe manquant', $responseBody['message']);
    }

    public function testConnexionWithMissingPassword()
    {
        $response = $this->client->post('connexion', [
            'form_params' => [
                'email' => 'test@example.com',
                'deviceId' => 'device123',
                'sct_code' => '12345',
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Email ou mot de passe manquant', $responseBody['message']);
    }

    //Read employe
    public function testSuccessfulReadEmploye()
    {
        $response = $this->client->get('read?id=8&boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('name', $responseBody[0]);
        $this->assertEquals('Jane', $responseBody[0]['name']);
    }

    public function testReadEmployeWithInvalidToken()
    {
        $response = $this->client->get('read', [
            'headers' => [
                'Authorization' => 'Bearer invalid_token',
            ],
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testReadEmployeWithMissingData()
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

    public function testReadEmployeWithoutToken()
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
    public function testSuccessfulReadAllEmploye()
    {
        $response = $this->client->get('readAll?boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('name', $responseBody[0]);
        $this->assertEquals('Jane', $responseBody[0]['name']);
    }

    public function testReadAllEmployeWithInvalidToken()
    {
        $response = $this->client->get('readAll', [
            'headers' => [
                'Authorization' => 'Bearer invalid_token',
            ],
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testReadAllEmployeWithMissingData()
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

    public function testReadAllEmployeWithoutToken()
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
    public function testSuccessfulUpdateEmploye()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id' =>  8,
                'name' => 'Jane',
                'surname' => 'Doe',
                'email' => 'janedoe@example.com',
                'phone' => '0987654321',
                'boutique_id' => 6,
                'solde_conges' => 15,
                'contrat' => 40,
                'color' => "#D5DADF"
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Jane', $responseBody[0]['name']);
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
                'id' => 1,
                'name' => 'Jane',
                'surname' => 'Doe',
                'email' => 'janedoe@example.com',
                'phone' => '0987654321',
                'boutique_id' => 6,
                'solde_conges' => 15,
                'contrat' => 40
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testUpdateEmployeWithInvalidEmail()
    {
        $response = $this->client->post('update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id' => 1,
                'name' => 'Jane',
                'surname' => 'Doe',
                'email' => 'invalid-email',
                'phone' => '0987654321',
                'boutique_id' => 6,
                'solde_conges' => 15,
                'contrat' => 40,
                'color' => "hjgjfhds"
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('email invalide', $responseBody['message']);
    }

    // test delete employe
    public function testSuccessfulDeleteEmploye()
    {
        $response = $this->client->delete('delete', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'id' => self::$user_id,
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Supprimer', $responseBody['message']);
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
