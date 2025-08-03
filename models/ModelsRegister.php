<?php

class ModelsRegister {
    private string $name = "";
    private string $surname = "";
    private string $email = "";
    private string $phone = "";
    private string $password_hashed = ""; // Renamed to clearly indicate it's hashed
    private string $password_confirm = ""; // Still useful for initial input validation

    public function __construct(
        string $name = "",
        string $surname = "",
        string $email = "",
        string $phone = ""
    ) {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
    }

    // Setters
    public function setName(string $name): void {
        $this->name = trim($name); // Trim whitespace
    }

    public function setSurname(string $surname): void {
        $this->surname = trim($surname); // Trim whitespace
    }

    public function setEmail(string $email): void {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format.");
        }
        $this->email = $email;
    }

    public function setPhone(string $phone): void {
        // Basic phone number sanitation/validation
        $phone = preg_replace('/[^0-9+]/', '', $phone); // Remove non-numeric except '+'
        if (strlen($phone) < 7) { // Example: minimum length
            throw new InvalidArgumentException("Invalid phone number format.");
        }
        $this->phone = $phone;
    }

    public function setPassword(string $password): void {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters long.");
        }
        // You might add more complex regex for password strength here
        $this->password_hashed = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setPasswordConfirm(string $password_confirm): void {
        // This setter is primarily for *input* validation, not for storing directly
        // The actual comparison would happen when processing the form
        $this->password_confirm = $password_confirm;
    }

    // Getters
    public function getName(): string {
        return $this->name;
    }

    public function getSurname(): string {
        return $this->surname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getPasswordHashed(): string {
        return $this->password_hashed;
    }

    public function getPasswordConfirm(): string {
        return $this->password_confirm;
    }

    // A method to verify a plain text password against the stored hash
    public function verifyPassword(string $plainTextPassword): bool {
        return password_verify($plainTextPassword, $this->password_hashed);
    }
}
?>
