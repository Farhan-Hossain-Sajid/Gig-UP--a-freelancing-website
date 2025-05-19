<?php
namespace App\Models;

class Baksh
{
    /**
     * Validate the submitted bKash details.
     */
    public function validate(array $data): bool
    {
        return isset($data['account'], $data['pin'])
            && preg_match('/^\d{11}$/', $data['account'])
            && preg_match('/^\d{4,6}$/', $data['pin']);
    }
}
