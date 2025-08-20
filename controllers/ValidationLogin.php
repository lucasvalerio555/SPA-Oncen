<?php
require_once '../libs/vendor/autoload.php';
require_once '../models/ModelsLogin.php';

class ValidationLogin
{
    private string $redirectUri = 'http://localhost/SPA_ONCE/index.php';
    private string $clientSecret = '../view/json/Data_credencialGoogleApi.json';

    private array $credentialFacebook = [
        'app_id' => 'TU_APP_ID',         // Cambia por tu App ID
        'app_secret' => 'TU_APP_SECRET', // Cambia por tu App Secret
        'default_graph_version' => 'v20.0',
    ];

    /**
     * Valida si los campos email y password no están vacíos
     */
    public function ValidationField(ModelsLogin $models): void
    {
        $email = trim($models->getEmail());
        $password = trim($models->getPassword());

        if (!empty($email) && !empty($password)) {
            echo "¡Correcto!";
            $this->sanetizacion(
                $email,
                '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                $password,
                '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'
            );
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Debes rellenar todos los campos !!!',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>";
        }
    }

    /**
     * Sanitización de email y password
     */
    public function sanetizacion(
        string $email,
        string $rexmail,
        string $password,
        string $rexpassword
    ): array {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = preg_replace('/[^\p{L}\p{N}\p{P}\p{S}]/u', '', $password);

        $emailValido = preg_match($rexmail, $email);
        $passwordValido = preg_match($rexpassword, $password);

        return [
            'email' => $emailValido ? $email : null,
            'password' => $passwordValido ? $password : null
        ];
    }

    /**
     * Inicia el login con Google
     */
    public function SignLoginGoogle(): void
    {
        session_start();

        $client = new Google_Client();
        $client->setAuthConfig($this->clientSecret);
        $client->setRedirectUri($this->redirectUri);
        $client->addScope('email');
        $client->addScope('profile');

        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit();
    }

    /**
     * Procesa el código devuelto por Google y obtiene información del usuario
     */
    public function requestCode(string $code): bool
    {
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

                header('Location: index.php?route=dashboard');
                exit;
            } else {
                error_log('Error al obtener el token: ' . $token['error']);
                return false;
            }
        } catch (Exception $e) {
            error_log('Excepción en requestCode: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Inicia sesión con Facebook y guarda el token en JSON
     */
    public function SignLoginFacebook(): void
    {
        session_start();

        $facebook = new \Facebook\Facebook($this->credentialFacebook);
        $helper = $facebook->getRedirectLoginHelper();

        try {
            if (isset($_GET['state'])) {
                $helper->getPersistentDataHandler()->set('state', $_GET['state']);
            }

            $accessToken = $helper->getAccessToken();
            
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            error_log('Graph API error: ' . $e->getMessage());
            exit;
            
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            error_log('Facebook SDK error: ' . $e->getMessage());
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                error_log("Error: " . $helper->getError() .
                    " Reason: " . $helper->getErrorReason() .
                    " Description: " . $helper->getErrorDescription());
            }
            exit;
        }

        // Guardar token corto
        $_SESSION['facebook_access_token'] = (string)$accessToken;

        // Convertir a token largo
        try {
            $oAuth2Client = $facebook->getOAuth2Client();
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            error_log('Error obteniendo token largo: ' . $e->getMessage());
        }

        // Guardar en JSON
        $tokenData = [
            'access_token' => $_SESSION['facebook_access_token'],
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        file_put_contents('../view/json/facebook_token.json', json_encode($tokenData, JSON_PRETTY_PRINT));

        // Obtener datos del usuario
        try {
            $response = $facebook->get('/me?fields=id,name,email,picture', $_SESSION['facebook_access_token']);
            $user = $response->getGraphUser();

            $_SESSION['user'] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'picture' => $user->getPicture()->getUrl()
            ];

            header('Location: index.php?route=dashboard');
            exit;
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            error_log('Error al obtener datos del usuario: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la URL de login de Facebook
     */
    public function getFacebookLoginUrl(): string
    {
        $facebook = new \Facebook\Facebook($this->credentialFacebook);
        $helper = $facebook->getRedirectLoginHelper();
        $permissions = ['email']; // Añadir más permisos si necesitas

        return $helper->getLoginUrl($this->redirectUri, $permissions);
    }
}
?>

