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
            "id" => 11,
            "roles" => "admin"
        ];

        $this->client = new Client([
            'base_uri' => 'http://localhost:9010/', // Remplacez par l'URL de base de votre API
        ]);
    }



    public function testSuccessfulCreateBoutique()
    {
        $response = $this->client->post('sign_up', [

            'form_params' => [
                "email" => "a.vincent.anthony@gmail.com",
                "password" => "securePassword123!",
                "social" => "Facebook",
                "boutiqueName" => "MaBoutique",
                "ste_code" => "STE123",
                "siret" => "12345678901234",
                "adress" => "123 Rue de l'Exemple",  
                "zipCode" => "75001",
                "city" => "Paris",
                "phoneNumber" => "+33123456789",
                "accept" => true
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$user_id = $responseBody["message"];

        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire

    }

    public function testAllreadyExistCreateBoutique()
    {
        $response = $this->client->post('sign_up', [

            'form_params' => [
                "email" => "a.vincent.anthony@gmail.com",
                "password" => "securePassword123!",
                "social" => "Facebook",
                "boutiqueName" => "MaBoutique",
                "ste_code" => "STE123",
                "siret" => "12345678901234",
                "adress" => "123 Rue de l'Exemple",  
                "zipCode" => "75001",
                "city" => "Paris",
                "phoneNumber" => "+33123456789",
                "accept" => true
            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals("L'utilisateur ou l'email existe déjà", $responseBody['message']);


    }
    public function testerrorMailCreateBoutique()
    {
        $response = $this->client->post('sign_up', [

            'form_params' => [
                "email" => "a.vincent.anthonygmail.com",
                "password" => "securePassword123!",
                "social" => "Facebook",
                "boutiqueName" => "MaBoutique",
                "ste_code" => "STE123",
                "siret" => "12345678901234",
                "adress" => "123 Rue de l'Exemple",  
                "zipCode" => "75001",
                "city" => "Paris",
                "phoneNumber" => "+33123456789",
                "accept" => true
            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx

        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals("Format de l'email incorrect", $responseBody['message']);

    }
    public function testerrorManqueDataCreateBoutique()
    {
        $response = $this->client->post('sign_up', [

            'form_params' => [
                "email" => "a.vincent.anthony@gmail.com",
                "password" => "securePassword123!",
                "boutiqueName" => "MaBoutique",
                "ste_code" => "STE123",
                "siret" => "12345678901234",
                "adress" => "123 Rue de l'Exemple",  
                "zipCode" => "75001",
                "city" => "Paris",
                "phoneNumber" => "+33123456789",
                "accept" => true
            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx

        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals("Données manquantes", $responseBody['message']);

    }

    public function testSuccesfullConnexion()
    {
        $response = $this->client->post('auth', [

            'form_params' => [
                "email" => "a.vincent.anthony@gmail.com",
                "password" => "securePassword123!",
            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx

        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire
        $this->assertArrayHasKey('token', $responseBody); // Exemple de vérification supplémentaire
        $this->assertArrayHasKey('refresh_token', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals("connexion ok", $responseBody['message']);

    }
    public function testErrorConnexion()
    {
        $response = $this->client->post('auth', [

            'form_params' => [
                "email" => "a.vincent.anthony@gmail.com",
                "password" => "securePassword12!",
            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx

        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals("Mot de passe ou identifiant incorrect", $responseBody['message']);

    }
    public function testErrorDataConnexion()
    {
        $response = $this->client->post('auth', [

            'form_params' => [
                "email" => "a.vincent.anthony@gmail.com",

            ],
            'http_errors' => false, // Pour éviter que Guzzle ne lève une exception sur une réponse 4xx ou 5xx

        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals("Données manquantes", $responseBody['message']);

    }
   
}
