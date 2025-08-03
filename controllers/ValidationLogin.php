<?php
require_once '../libs/vendor/autoload.php';

class ValidationLogin {

    private $redirectUri = 'http://localhost/SPA_ONCE/index.php';
    private $clientSecret = '../view/json/Data_credencialGoogleApi.json'; // Ruta a tu client_secret.json

    public function SignLoginGoogle() {
        session_start();

        $client = new Google_Client();
        $client->setAuthConfig($this->clientSecret);
        $client->setRedirectUri($this->redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        $auth_url = $client->createAuthUrl();

        // Redireccionar al usuario
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit();
    }

    public function requestCode(string $code) {
        session_start();

        $client = new Google_Client();
        $client->setAuthConfig($this->clientSecret);
        $client->setRedirectUri($this->redirectUri);

        try {
            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (!isset($token['error'])) {
                $client->setAccessToken($token);
                $_SESSION['access_token'] = $token;

                $service = new Google_Service_Oauth2($client);
                $user = $service->userinfo->get();

                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'picture' => $user->getPicture()
                ];
                return true;
            } else {
                // Manejar error
                error_log('Error al obtener el token: ' . $token['error']);
                return false;
            }
        } catch (Exception $e) {
            error_log('Excepción en requestCode: ' . $e->getMessage());
            return false;
        }
    }
}
?>

