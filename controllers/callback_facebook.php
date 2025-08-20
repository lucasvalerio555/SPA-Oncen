<?php
require_once '../libs/vendor/autoload.php';
require_once '../models/ModelsLogin.php';
require_once '../controllers/ValidationLogin.php';

session_start();

// Instancia de la clase que creamos
$login = new ValidationLogin();

// Credenciales de Facebook
$facebook = new \Facebook\Facebook([
    'app_id' => 'TU_APP_ID',       // Cambia por tu App ID
    'app_secret' => 'TU_APP_SECRET', // Cambia por tu App Secret
    'default_graph_version' => 'v20.0',
]);

$helper = $facebook->getRedirectLoginHelper();

try {
    // Necesario para evitar error "Cross-site request forgery validation failed"
    if (isset($_GET['state'])) {
        $helper->getPersistentDataHandler()->set('state', $_GET['state']);
    }

    // Obtener token
    $accessToken = $helper->getAccessToken();
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    exit('Error Graph API: ' . $e->getMessage());
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    exit('Error Facebook SDK: ' . $e->getMessage());
}

if (!isset($accessToken)) {
    exit('No se pudo obtener el token.');
}

// Guardar token corto en sesión
$_SESSION['facebook_access_token'] = (string)$accessToken;

// OPCIONAL: intercambiar por token largo
try {
    $oAuth2Client = $facebook->getOAuth2Client();
    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    exit('Error obteniendo token largo: ' . $e->getMessage());
}

// Guardar token en archivo JSON
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
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    exit('Error al obtener datos del usuario: ' . $e->getMessage());
}

// Redirigir al dashboard
header('Location: ../index.php?route=dashboard');
exit;

