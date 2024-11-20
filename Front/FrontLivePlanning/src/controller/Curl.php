<?php

namespace App\Controller;

class Curl
{
    public string $returnServer;
    public string | null $message;
    /**
     * Authentifie l'utilisateur et stocke le token dans une session
     */
    public function authUser($username, $password): bool
    {
        $data = [
            'email' => $username,
            'password' => $password,
        ];
        $res = $this->commonCurlPOST($data, URLAUTH . "/auth", 200);

        if ($res !== false) {
            $response = json_decode($this->returnServer, true);
            $jwt = new JWTController();
            $jwtOpen = $jwt->decodeJwt($response['token']);
            $_SESSION['token'] = $response['token'];
            $_SESSION['refresh_token'] = $response['refresh_token'];
            $_SESSION['role'] = $jwtOpen->other->roles ?? "";
            $_SESSION['boutique'] = $response['boutique'];
            $_SESSION['idBoutique'] = $response['boutique']["id"];
            $_SESSION['options'] = json_decode($response['boutique']["options"] ?? "[]", true);
            $data = $_SESSION;
            $this->createCookieConnexion($data);
            
            return true;
        }
        if ($res !== false && isset($this->returnServer)) {
            $response = json_decode($this->returnServer, true);

            return true;
        }

        return false;
    }

    /**
     * Gestion du cookie de connexion
     */
    public function createCookieConnexion($data)
    {
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        // Chiffrement
        $encryptedData = openssl_encrypt(json_encode($data), 'aes-256-cbc', SECRET_KEY, 0, $iv);
        $encodedData = base64_encode($iv . $encryptedData);
        setcookie('user_data', $encodedData, [
            'expires' => time() + (86400 * 100), // Expiration dans 30 jours
            'path' => '/',
            'secure' => true,       // Assure l'envoi uniquement via HTTPS
            'httponly' => true,     // Empêche l'accès par JavaScript
            'samesite' => 'Lax',    // Restreint les envois intersites
        ]);
        
        
    }

    public function decodeCookieConnexion():bool{
        if (isset($_COOKIE['user_data'])) {
            $cookieData = base64_decode($_COOKIE['user_data']);
            $iv = substr($cookieData, 0, openssl_cipher_iv_length('aes-256-cbc'));
            $encryptedData = substr($cookieData, openssl_cipher_iv_length('aes-256-cbc'));
            $decodedData = openssl_decrypt($encryptedData, 'aes-256-cbc', SECRET_KEY , 0, $iv);

            $retrievedData = json_decode($decodedData, true);
            $_SESSION = $retrievedData;
            return true;
        }
        return false;
    }
    /**
     * envoie une requete de demande d'activation du compte client
     */
    public function activateUser($token, $email): bool
    {
        $data = [
            'token' => $token,
            'email' => $email
        ];
        $res = $this->commonCurlPut($data, URLAUTH . "/activation?token=$token&email=$email", 200);
        if (isset($this->returnServer, json_decode($this->returnServer, true)["message"])) {
            $this->message =  json_decode($this->returnServer, true)["message"];
        }
        if ($res !== false) {
            return true;
        }
        return false;
    }
    /**
     * Envoie une inscription
     */
    public function inscriptionUser(array $data)
    {
        $data = [
            'password' => $data["password"],
            'email' => $data["email"],
            'social' => $data["social"],
            'boutiqueName' => $data["boutiqueName"],
            'siret' => $data["siret"],
            'ste_code' => $data["ste_code"],
            'adress' => $data["adress"],
            'zipCode' => $data["zipCode"],
            'city' => $data["city"],
            'phoneNumber' => $data["phoneNumber"],
            'accept' => $data["accept"],
            'checkOut' => $_SERVER['HTTP_ORIGIN'] . "/activation"
        ];
        $res = $this->commonCurlPOST($data, URLAUTH . "/sign_up", 201);
        if (isset($this->returnServer, json_decode($this->returnServer, true)["message"])) {
            $this->message =  json_decode($this->returnServer, true)["message"];
        }



        if ($res !== false && isset($this->returnServer)) {
            $response = json_decode($this->returnServer, true);
            $this->stripeAddClient($data);
            return true;
        }
        // Initialisation de cURL

        return false;
    }

    public function stripeAddClient($data)
    {
        $stripe = new \Stripe\StripeClient(STRIPE_KEY);
        $stripe->customers->create([
            'email' => $data["email"],
            'name' => $data["social"] . " " . $data["boutiqueName"],
            'address' => [
                'line1' => $data['adress'],
                'postal_code' => $data["zipCode"],
                'city' => $data["city"],
                'country' => "FR"
            ],

            'phone' => $data["phoneNumber"],

        ]);
    }
    public function refreshConnexion($refreshToken)
    {
        $data = [
            'refresh_token' => $refreshToken
        ];
        $res = $this->commonCurlPOST($data, URLAUTH . "/refreshToken", 200);

        if ($res !== false) {
            $response = json_decode($this->returnServer, true);
            ////var_dump($response);
            $_SESSION['token'] = $response['token'];
            $this->createCookieConnexion($_SESSION);

            ////var_dump($_SESSION);
            return true;
        }

        return false;
    }
    /**
     * 
     */
    public function forgetPassword($username)
    {
        $data = [
            "email" => $username,
            "checkOut" => URL . "/reinsmdp"
        ];
        return $this->commonCurlPOST($data, URLAUTH . '/forgetPassword', 200);
    }
    /**
     * methode qui génère la requete curl en post
     * @param array $data
     * @param string $url 
     * @param int $httpAttemp code de répose http attendu
     * 
     * @return bool
     */
    public function commonCurlPOST(array $data, string $url, int $httpAttempt, array $headers = null): bool
    {

        $ch = curl_init($url);


        $data = $this->sanitizeHTML($data);


        // Configuration des options cURL
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($headers) && is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // Exécution de la requête cURL
        $response = curl_exec($ch);


        // Gestion des erreurs
        if (curl_errno($ch)) {
            //echo 'Erreur cURL : ' . curl_error($ch);
        }
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Fermeture de la session cURL
        curl_close($ch);

        $this->returnServer = $response;
        //var_dump($response);
        if ($httpAttempt !== $httpStatus) {
            //
           // echo $httpStatus;
            return false;
        }


        return true;
    }



    public function commonCurlPut($data, string $url, int $httpAttempt, $headers = null)
    {
        $data = $this->sanitizeHTML($data);
        // Initialisation de cURL
        $ch = curl_init($url);

        if (isset($headers) && is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // Configuration des options cURL
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécution de la requête cURL
        $response = curl_exec($ch);

        // Vérification des erreurs
        if (curl_errno($ch)) {
            ////echo 'Erreur cURL : ' . curl_error($ch);
        }
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Fermeture de la session cURL
        curl_close($ch);

        $this->returnServer = $response;
        //var_dump($response);
        if ($httpAttempt !== $httpStatus) {
            ////echo $httpStatus;
            return false;
        }


        return true;
    }

    public function commonCurlGet($url, $headers, $httpAttempt)
    {


        // URL de la requête GET


        // Création de la ressource cURL
        $ch = curl_init($url);

        if (isset($headers) && is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // Configuration des options cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Exécution de la requête cURL et récupération de la réponse
        $response = curl_exec($ch);

        // Vérification des erreurs
        if (curl_errno($ch)) {
            //echo 'Erreur cURL : ' . curl_error($ch);
        }
        $this->returnServer = $response;
        //var_dump($this->returnServer);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpAttempt !== $httpStatus) {
            //echo $httpStatus;
            return false;
        }
        // Fermeture de la ressource cURL
        curl_close($ch);

        // Traitement de la réponse
        return true;
    }
    protected function commonCurlDelete($url, int $httpAttempt,  $headers = null)
    {
        $ch = curl_init();

        if (isset($headers) && is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Configuration des options cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécution de la requête cURL
        $response = curl_exec($ch);

        // Gestion des erreurs
        if (curl_errno($ch)) {
            ////echo 'Erreur cURL : ' . curl_error($ch);
        }
        echo $response;
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpAttempt !== $httpStatus) {
            ////echo $httpStatus;
            return false;
        }
        $this->returnServer = $response;
        // Fermeture de la session cURL
        curl_close($ch);

        // Traitement de la réponse
        return true;
    }


    protected function commonCurlPOSTImage(array $data, string $url, int $httpAttempt, array $headers = null): bool
    {

        $ch = curl_init($url);





        // Configuration des options cURL
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($headers) && is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // Exécution de la requête cURL
        $response = curl_exec($ch);


        ////echo $response;
        // Gestion des erreurs
        if (curl_errno($ch)) {
            //echo 'Erreur cURL : ' . curl_error($ch);
            return false;
        }
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Fermeture de la session cURL
        curl_close($ch);

        $this->returnServer = $response;
        //echo $response;
        if ($httpAttempt !== $httpStatus) {
            //echo $httpStatus;
            return false;
        }


        return true;
    }

    public function escapeScriptTags($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // Appel récursif pour les tableaux imbriqués
                $array[$key] = $this->escapeScriptTags($value);
            } else {
                // Échapper les balises script dans la valeur
                $array[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        return $array;
    }
    public function sanitizeHTML($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // Appel récursif pour les tableaux imbriqués
                $array[$key] = $this->sanitizeHTML($value);
            } else {
                // Échapper les balises script dans la valeur
                if (!empty($array[$key])) {
                    $array[$key] = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $array[$key]);
                }
            }
        }

        return $array;
    }
}
