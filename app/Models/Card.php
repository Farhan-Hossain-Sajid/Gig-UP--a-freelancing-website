<?php
namespace App\Models;

class Card
{
    /**
     * Validate the submitted card details.
     */
    public function validate(array $data): bool
    {
        return isset($data['card_number'], $data['validity'], $data['cvc'])
            && preg_match('/^\d{16}$/', $data['card_number'])
            && preg_match('/^\d{2}\/\d{2}$/', $data['validity'])
            && preg_match('/^\d{3}$/', $data['cvc']);
    }
}
