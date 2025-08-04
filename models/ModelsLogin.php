<?php  
//require_once __DIR__ . '/Models.php'; // Ruta relativa al archivo Models.php

class ModelsLogin{
    private ?string $email = null;
    private ?string $password = null;

    public function setEmail(string $email): void {
        $this->email = $email;  
    }	

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;  
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function isField(): bool {
        return !empty($this->email) && !empty($this->password);	   
    }

    public function serverLogin() {
        // Lógica para validar login
        return;
    }
}
?>

