<?php
// controllers/ValidationRegister.php

require_once __DIR__ . '/../models/ModelsRegister.php';
require_once __DIR__ . '/../config/settingDB.php';
$config = require_once __DIR__ . '/../config/config.php';

class ValidationRegister {
    public static array $index = []; // array global estático

    /**
     * Procesa el formulario de registro.
     * @return array Resultado con 'success', 'errors', 'data' y 'index'
     */
    public static function processForm(): array {
        $errors = [];
        $data = [];
        self::$index = []; // limpiar antes de usar

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Sanear datos
                $data = self::sanitizeData($_POST);

                // Validar campos obligatorios
                $errors = self::validateRequiredFields($data);

                // Validar email
                $errors = array_merge($errors, self::validateEmail($data['email']));

                // Validar sexo
                $errors = array_merge($errors, self::validateSexo($data['sexo']));

                // Validar formato del teléfono
                $errors = array_merge($errors, self::validatePhone($data['phone']));

                // Validar contraseñas
                $errors = array_merge($errors, self::validatePasswords($_POST['password'] ?? '', $_POST['password-confirm'] ?? ''));

                // Si no hay errores, setear datos al modelo
                if (empty($errors)) {
                    $register = new ModelsRegister();
                    $register->setName($data['name']);
                    $register->setSurname($data['surname']);
                    $register->setEmail($data['email']);
                    $register->setPhone($data['phone']);
                    $register->setPassword($_POST['password']); // Hasheo interno esperado
                }
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            } catch (Exception $e) {
                $errors[] = "Ocurrió un error inesperado. Intenta nuevamente.";
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

    /**
     * Sanear datos del formulario
     */
    private static function sanitizeData(array $input): array {
        return [
            'name'    => trim($input['name'] ?? ''),
            'surname' => trim($input['surname'] ?? ''),
            'email'   => filter_var(trim($input['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'phone'   => preg_replace('/\D+/', '', $input['phone'] ?? ''),
            'sexo'    => $input['sexo'] ?? ''
        ];
    }

    /**
     * Valida campos obligatorios y guarda los índices de los que fallan
     */
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
                self::$index[] = $count; // guardamos el índice
            }
            $count++;
        }
        return $errors;
    }

    /**
     * Valida el email
     */
    private static function validateEmail(string $email): array {
        return !filter_var($email, FILTER_VALIDATE_EMAIL)
            ? ["El formato del email no es válido."]
            : [];
    }

    /**
     * Valida el campo sexo
     */
    private static function validateSexo(string $sexo): array {
        return (!in_array($sexo, ['Hombre', 'Mujer']))
            ? ["Valor de sexo no válido."]
            : [];
    }

    /**
     * Valida el formato del teléfono usando expresión regular
     */
    private static function validatePhone(string $phone): array {
        // Regex que permite números tipo +598..., 09..., con o sin espacios o guiones
        $pattern = '/^\+?\d{0,3}?[-.\s]?(\(?\d{1,4}\)?)?[-.\s]?\d{6,10}$/';
        return !preg_match($pattern, $phone)
            ? ["El formato del teléfono no es válido."]
            : [];
    }

    /**
     * Valida las contraseñas
     */
    private static function validatePasswords(string $pass, string $confirm): array {
        $errors = [];
        if (strlen($pass) < 8) {
            $errors[] = "La contraseña debe tener al menos 8 caracteres.";
        }
        if ($pass !== $confirm) {
            $errors[] = "Las contraseñas no coinciden.";
        }
        return $errors;
    }

    /**
     * Muestra errores de campos vacíos según índices
     */
    public static function FieldIsEmpty(array $errors): void {
        foreach (self::$index as $i) {
            echo '<p class="wrating">' . htmlspecialchars($errors[$i]) . '</p>';
        }
    }
    
    
    /**
    	Método, encargado de almacenar los datos registrado de usuario que
    	se registro en la base de datos.
    **/
    
    
    public static function SaveRegisterDB(array $field, $config){
    	$settingDB =new settingDB($config);
    
    }
    
    /**
     * Genera el script del toast con slider animado.
     */
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

