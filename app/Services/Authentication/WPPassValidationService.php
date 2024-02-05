<?php

namespace App\Services\Authentication;

class WPPassValidationService
{
    protected $wpHasher;

    public function __construct()
    {
        // Include WordPress's Password Hashing Library (PHPass)
        require_once base_path('helpers/class-phpass.php');

        // Instantiate PasswordHash
        $this->wpHasher = new \PasswordHash(8, true);
    }

    public function validatePassword($plainPassword, $hashedPassword)
    {
        // Check if the password matches the hashed password using WordPress's method
        return $this->wpHasher->CheckPassword($plainPassword, $hashedPassword);
    }

    public function hashPassword($plainPassword)
    {
        // Hash the password using WordPress's hashing method
        return $this->wpHasher->HashPassword($plainPassword);
    }
}
