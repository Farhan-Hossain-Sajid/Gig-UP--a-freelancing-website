<?php
namespace App\Models;

class AdminLogin
{
    private string $email = 'admin@gmail.com';
    private string $pin   = '12345';

    /**
     * Check admin credentials.
     */
    public function attempt(string $email, string $pin): bool
    {
        return $email === $this->email && $pin === $this->pin;
    }
}
