<?php
namespace App\Models;

class Nagad
{
    /**
     * Validate the submitted Nagad details.
     */
    public function validate(array $data): bool
    {
        return isset($data['account'], $data['pin'])
            && preg_match('/^\d{11}$/', $data['account'])
            && preg_match('/^\d{4}$/', $data['pin']);
    }
}
