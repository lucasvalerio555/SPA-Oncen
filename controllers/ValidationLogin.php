<?php
require_once '../libs/vendor/autoload.php';
require_once '../models/ModelsLogin.php';

class ValidationLogin {

    private string $redirectUri = 'http://localhost/SPA_ONCE/index.php';
    private string $clientSecret = '../view/json/Data_credencialGoogleApi.json';

    private array $credentialFacebook = [
        'app_id' => 'TU_APP_ID',
        'app_secret' => 'TU_APP_SECRET',
        'default_graph_version' => 'v2.10',
    ];

    /**
     * Valida si los campos email y password no están vacíos
     */
    public function ValidationField(ModelsLogin $models): void {
        $email = trim($models->getEmail());
        $password = trim($models->getPassword());

        if (!empty($email) && !empty($password)) {
            echo "¡Correcto!";
            sanetizacion($email,'/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            $password,'/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/');
            
        } else {
            echo "<script>
		     Swal.fire({icon:'warning',title:
		     '⚠️ Debes rellenar todos los campos !!!',showConfirmButton:false,timer:2500});
	        </script>";
        }
    }

    /**
     * Ejemplo de sanitización de datos con expresiones regulares
     */
    public function sanetizacion(string $email, string $rexmail, string $password, string $rexpassword): array {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

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
    public function SignLoginGoogle(): void {
        session_start();

        $client = new Google_Client();
        $client->setAuthConfig($this->clientSecret);
        $client->setRedirectUri($this->redirectUri);
        $client->addScope('email');
        $client->addScope('profile');

        $auth_url = $client->createAuthUrl();

        // Redirigir al usuario
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit();
    }

    /**
     * Procesa el código devuelto por Google y obtiene la información del usuario
     */
    public function requestCode(string $code): bool {
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
                //return true;
                // Redirigir a zona segura
		header('Location: index.php?route=dashborad');
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
    
   public function SignLoginFacebook(): void {
      session_start();
    
      $facebook = new \Facebook\Facebook($this->credentialFacebook);
      $helper = $facebook->getRedirectLoginHelper();

      try {
        $accessToken = $helper->getAccessToken();
      } catch (\Facebook\Exceptions\FacebookResponseException $e) {
        error_log('Graph returned an error: ' . $e->getMessage());
        exit;
      } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        error_log('Facebook SDK returned an error: ' . $e->getMessage());
        exit;
      }

      if (isset($accessToken)) {
        $_SESSION['facebook_access_token'] = (string) $accessToken;

        // Obtener datos del usuario
        try {
            $response = $facebook->get('/me?fields=id,name,email,picture', $accessToken);
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
   }
   
   public function getFacebookLoginUrl(): string {
    $facebook = new \Facebook\Facebook($this->credentialFacebook);
    $helper = $facebook->getRedirectLoginHelper();
    $permissions = ['email']; // Puedes agregar más permisos

    return $helper->getLoginUrl($this->redirectUri, $permissions);
   }
}
?>

