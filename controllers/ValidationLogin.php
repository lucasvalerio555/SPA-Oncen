<?php
require_once __DIR__ . '/../libs/vendor/autoload.php';
require_once __DIR__ . '/../models/ModelsLogin.php';
require_once __DIR__ . '/../config/settingDB.php';
require_once __DIR__ . '/../config/config.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class ValidationLogin
{
    private string $redirectUri = 'http://localhost/SPA_ONCE/index.php';
    private string $clientSecret = __DIR__ . '/../view/json/Data_credencialGoogleApi.json';

    private array $credentialFacebook = [
        'app_id' => 'TU_APP_ID',         // Cambia por tu App ID
        'app_secret' => 'TU_APP_SECRET', // Cambia por tu App Secret
        'default_graph_version' => 'v20.0',
    ];

    /**
     * Inicia sesión segura si no está ya iniciada
     */
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Valida si los campos email y password no están vacíos
     */
    public function ValidationField(ModelsLogin $models): ?array
    {
        $email = trim($models->getEmail());
        $password = trim($models->getPassword());

        if (empty($email) || empty($password)) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Debes rellenar todos los campos !!!',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>";
            return null;
        }

        // Validar formato
        $emailValido = filter_var($email, FILTER_VALIDATE_EMAIL);
        $passwordValido = preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password);

        if (!$emailValido || !$passwordValido) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: '❌ Formato de correo o contraseña inválido',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>";
            return null;
        }

        return [
            'email' => $email,
            'password' => $password
        ];
    }

    /**
     * Inicia el login con Google
     */
    public function SignLoginGoogle(): void
    {
        $this->startSession();

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
        $this->startSession();

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
        $this->startSession();

        $facebook = new Facebook($this->credentialFacebook);
        $helper = $facebook->getRedirectLoginHelper();

        try {
            if (isset($_GET['state'])) {
                $helper->getPersistentDataHandler()->set('state', $_GET['state']);
            }

            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            error_log('Graph API error: ' . $e->getMessage());
            exit;
        } catch (FacebookSDKException $e) {
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
            
        } catch (FacebookSDKException $e) {
            error_log('Error obteniendo token largo: ' . $e->getMessage());
        }

        // Guardar en JSON
        $tokenData = [
            'access_token' => $_SESSION['facebook_access_token'],
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        file_put_contents(__DIR__ . '/../view/json/facebook_token.json', json_encode($tokenData, JSON_PRETTY_PRINT));

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
        } catch (FacebookResponseException $e) {
            error_log('Error al obtener datos del usuario: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la URL de login de Facebook
     */
    public function getFacebookLoginUrl(): string
    {
        $facebook = new Facebook($this->credentialFacebook);
        $helper = $facebook->getRedirectLoginHelper();
        $permissions = ['email']; // Añadir más permisos si necesitas

        return $helper->getLoginUrl($this->redirectUri, $permissions);
    }

    /**
     * Login clásico con email y contraseña
     */
    public function SignLogin(array $field, $config): void
    {
        $this->startSession();

        $settingDB = new SettingDB($config);
        $settingDB->select(
            "SELECT u.idUsuario, u.Password, p.Email 
             FROM Usuarios u
             INNER JOIN Personas p ON u.PersonaId = p.idPersona
             WHERE p.Email = ?"
        );
        
        $settingDB->execute([$field['email']]);
        $result = $settingDB->getResult();

        if (count($result) > 0) {
            $row = $result[0];

            if (!empty($row['Password']) && password_verify($field['password'], $row['Password'])) {
                $_SESSION['user'] = [
                    'id' => $row['idUsuario'],
                    'email' => $row['Email']
                ];
                
                header('Location: index.php?route=dashboard');
                exit;
            }
        }

        echo "<p class='warning'>El correo y/o la contraseña son incorrectos</p>";
    }
}
?>

