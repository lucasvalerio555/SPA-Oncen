<?php
// controllers/ValidationRegister.php

require_once __DIR__ . '/../models/ModelsRegister.php';
require_once __DIR__ . '/../config/settingDB.php';
$config = require_once __DIR__ . '/../config/config.php';

class ValidationRegister {
    public static array $index = []; // array global estático

    public static function processForm(): array {
        $errors = [];
        $data = [];
        self::$index = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Saneamos los datos
                $data = self::sanitizeData($_POST);

                // Validar campos obligatorios
                $errors = self::validateRequiredFields($data);

                // Validar email
                $errors = array_merge($errors, self::validateEmail($data['email']));

                // Validar sexo
                $errors = array_merge($errors, self::validateSexo($data['sexo']));

                // Validar teléfono
                $errors = array_merge($errors, self::validatePhone($data['phone']));

                // Validar contraseñas
                $pass = $_POST['password'] ?? '';
                $confirm = $_POST['password-confirm'] ?? '';
                $errors = array_merge($errors, self::validatePasswords($pass, $confirm));

                // Si no hay errores, guardar en modelo y base de datos
                if (empty($errors)) {
                    $register = new ModelsRegister();
                    $register->setName($data['name']);
                    $register->setSurname($data['surname']);
                    $register->setEmail($data['email']);
                    $register->setPhone($data['phone']);

                    // ✅ Hashear contraseña antes de guardar
                    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
                    $register->setPassword($hashedPassword);

                    // Guardar en DB
                    self::saveRegisterDB([
                        'nombre'   => $data['name'],
                        'apellido' => $data['surname'],
                        'email'    => $data['email'],
                        'telefono' => $data['phone'],
                        'sexo'     => $data['sexo'],
                        'Pass'     => $hashedPassword
                    ], $config);
                }

            } catch (Exception $e) {
                $errors[] = "Ocurrió un error inesperado: " . $e->getMessage();
            }

            return [
                'success' => empty($errors),
                'errors'  => $errors,
                'data'    => $data,
                'index'   => self::$index
            ];
        }

        return ['success' => false, 'errors' => [], 'data' => [], 'index' => []];
    }

    private static function sanitizeData(array $input): array {
        return [
            'name'    => trim($input['name'] ?? ''),
            'surname' => trim($input['surname'] ?? ''),
            'email'   => filter_var(trim($input['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'phone'   => preg_replace('/\D+/', '', $input['phone'] ?? ''),
            'sexo'    => $input['sexo'] ?? ''
        ];
    }

    private static function validateRequiredFields(array $data): array {
        $errors = [];
        $required = [
            'name'    => 'El nombre es obligatorio.',
            'surname' => 'El apellido es obligatorio.',
            'email'   => 'El email es obligatorio.',
            'phone'   => 'El teléfono es obligatorio.',
            'sexo'    => 'El sexo es obligatorio.'
        ];

        $count = 0;
        foreach ($required as $field => $msg) {
            if (empty($data[$field])) {
                $errors[] = $msg;
                self::$index[] = $count;
            }
            $count++;
        }
        return $errors;
    }

    private static function validateEmail(string $email): array {
        return !filter_var($email, FILTER_VALIDATE_EMAIL)
            ? ["El formato del email no es válido."]
            : [];
    }

    private static function validateSexo(string $sexo): array {
        return !in_array($sexo, ['Hombre', 'Mujer'])
            ? ["Valor de sexo no válido."]
            : [];
    }

    private static function validatePhone(string $phone): array {
        // Uruguay: 09xxxxxxx o +598xxxxxxxx
        $pattern = '/^(09\d{7}|\+598\d{8})$/';
        return !preg_match($pattern, $phone)
            ? ["El formato del teléfono no es válido."]
            : [];
    }

    private static function validatePasswords(string $pass, string $confirm): array {
        $errors = [];
        if (strlen($pass) < 8) $errors[] = "La contraseña debe tener al menos 8 caracteres.";
        if ($pass !== $confirm) $errors[] = "Las contraseñas no coinciden.";
        return $errors;
    }

    public static function FieldIsEmpty(array $errors): void {
        foreach (self::$index as $i) {
            echo '<p class="wrating">' . htmlspecialchars($errors[$i]) . '</p>';
        }
    }

    private static function saveRegisterDB(array $field, $config): void {
      $settingDB = new settingDB($config);

      // Fijar el 
      rol
      	$tipoRol = "Cliente";
      	$statusPerson= "Habilitado";
      	$statusUser="Activo";
      	

      // Verificar duplicados por email en Usuarios
      $existingUser = $settingDB->select(
         "SELECT Tipo_Rol FROM Usuarios WHERE Email = ?",
         [$field['email']]
      );

     // Verificar duplicados en Personas
     $existingPepole = $settingDB->select(
        "SELECT Nombre, Apellido, Email, Telefono, Sexo 
         FROM Personas 
         WHERE Nombre = ? AND Apellido = ? AND Telefono = ?",
        [$field['nombre'], $field['apellido'], $field['telefono']]
     );

     if ($existingUser || $existingPepole) {
         echo self::generateToastScript('warning', "El Usuario ya ha sido Registrado!!!.");
         return;
     }

     // Insertar nuevo usuario en Personas
     $settingDB->insert(
        "INSERT INTO Personas (Nombre, Apellido, Email, Telefono, Sexo, Estado_Persona) VALUES (?, ?, ?, ?, ?, ?)",
        [$field['nombre'], $field['apellido'], $field['email'], $field['telefono'], $field['sexo'],$statusPerson]
     );

     // ✅ Insertar rol en Usuarios con contraseña hasheada
     $settingDB->insert(
        "INSERT INTO Usuarios (Tipo_Rol, Pass, Estado_Usuario) VALUES (?, ?, ?)",
        [$tipoRol, $field['Pass'], $statusUser]
     );

     echo self::generateToastScript('success', "El Usuario ha sido Registrado Correctamente!!!.");
 }

    public static function generateToastScript(string $icon, string $title, string $accentColor = '#4caf50'): string {
        return "<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
    const slider = document.createElement('input');
    slider.type = 'range';
    slider.min = 0;
    slider.max = 100;
    slider.value = 0;
    slider.style.width = '100%';
    slider.style.accentColor = '$accentColor';
    toast.appendChild(slider);

    let start = Date.now();
    let duration = 3000;
    let interval = setInterval(() => {
      let elapsed = Date.now() - start;
      let percent = Math.min((elapsed / duration) * 100, 100);
      slider.value = percent;
      if (percent >= 100) clearInterval(interval);
    }, 50);
  }
});
Toast.fire({ icon: '$icon', title: '$title' });
setTimeout(() => { window.location.href = 'index.php'; }, 3100);
</script>";
    }
}

