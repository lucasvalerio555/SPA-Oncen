<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../libs/vendor/autoload.php';

class ValidationMail {
    private ?PHPMailer $mail;

    public function __construct() {
        try {
            $this->mail = new PHPMailer(true);

            // Configuración del servidor SMTP
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;

            // Usa variables de entorno para seguridad
            $this->mail->Username   = getenv('MAIL_USER') ?: 'spatermaloncespatermalonce@gmail.com';
            $this->mail->Password   = getenv('MAIL_PASS') ?: 'iyel xrvf wwkt qjwc';

            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;
            $this->mail->CharSet    = 'UTF-8';

            // Remitente
            $this->mail->setFrom($this->mail->Username, 'SPA Termal Once');

        } catch (Exception $e) {
            error_log("❌ Error al inicializar PHPMailer: {$e->getMessage()}");
            $this->mail = null;
        }
    }

    /**
     * Valida si algún campo está vacío (después de hacer trim)
     */
    private function isEmptyField($firstName, $email, $userMsg): bool {
        return empty(trim($firstName)) || empty(trim($email)) || empty(trim($userMsg));
    }

    /**
     * Valida campos y envía el correo.
     * Devuelve 'success', 'error' o 'empty' para que la capa de presentación decida el mensaje.
     */
    public function validateAndSend($firstName, $email, $userMsg): string {
        if ($this->mail === null) {
            error_log("❌ PHPMailer no está inicializado.");
            return 'error';
        }

        // Sanitizar entradas
        $firstName = trim($firstName);
        $email     = trim($email);
        $userMsg   = trim($userMsg);

        if ($this->isEmptyField($firstName, $email, $userMsg)) {
            return 'empty';
        }

        // Generar HTML dinámico con nombre y mensaje del usuario
        $emailHtml = $this->renderWelcomeEmail($firstName, $userMsg);

        // Datos del correo
        $to      = 'spatermaloncespatermalonce@gmail.com';
        $toName  = 'Equipo, SPA Termal Once';
        $subject = 'Nuevo mensaje de contacto desde el sitio';

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to, $toName);

            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $emailHtml;
            $this->mail->AltBody = "Nuevo mensaje de $firstName:\n\n$userMsg";

            error_log("ℹ️ Intentando enviar correo a $to");

            if ($this->mail->send()) {
                error_log("✅ Correo enviado correctamente a $to");
                return 'success';
            } else {
                error_log("❌ Error al enviar correo: " . $this->mail->ErrorInfo);
                return 'error';
            }
        } catch (Exception $e) {
            error_log("❌ Excepción al enviar el correo: {$e->getMessage()}");
            return 'error';
        }
    }

    /**
     * Genera el HTML del correo con nombre y mensaje personalizados
     */
    private function renderWelcomeEmail(string $name, string $userMessage): string {
        $nameSafe = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $messageSafe = nl2br(htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8'));

        $description = "Gracias por ponerte en contacto con nosotros. Hemos recibido tu mensaje y nuestro equipo lo revisará con atención. Mientras tanto, te invitamos a disfrutar de todos los beneficios de nuestro spa termal.";
        $descriptionSafe = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Correo de Bienvenida</title>
<style>
  body {
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333333;
  }
  .container {
    max-width: 600px;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: fadeSlideUp 1s ease-out;
  }
  @keyframes fadeSlideUp {
    from {
      opacity: 0;
      transform: translateY(40px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .header {
    background: linear-gradient(90deg, #0d6efd, #6610f2);
    color: #ffffff;
    text-align: center;
    padding: 30px 20px;
  }
  .header h2 {
    margin: 0;
    font-size: 26px;
  }
  .content {
    padding: 30px 20px;
    text-align: center;
  }
  .content h1 {
    font-size: 22px;
    margin-bottom: 15px;
    color: #0d6efd;
  }
  .content p {
    font-size: 16px;
    margin-bottom: 15px;
    line-height: 1.6;
  }
  .user-message {
    font-style: italic;
    background: #f1f1f1;
    border-left: 4px solid #0d6efd;
    padding: 10px 15px;
    margin-bottom: 30px;
  }
  .btn {
    display: inline-block;
    padding: 12px 30px;
    background-color: #0d6efd;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.3s ease;
  }
  .btn:hover {
    background-color: #084298;
    transform: scale(1.05);
  }
  .footer {
    background-color: #f8f9fa;
    text-align: center;
    font-size: 12px;
    color: #888888;
    padding: 15px 10px;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>🌟 Bienvenido a Nuestro Servicio 🌟</h2>
    </div>
    <div class="content">
      <h1>Hola, {$nameSafe}!</h1>
      <p>{$descriptionSafe}</p>
      <div class="user-message">Tu mensaje:<br>{$messageSafe}</div>
      <a href="https://tusitio.com/confirmar?token=ejemplo" class="btn">Confirmar correo</a>
    </div>
    <div class="footer">
      © 2025 Tu Empresa. Todos los derechos reservados.
    </div>
  </div>
</body>
</html>
HTML;
    }
}
?>

