<?php
namespace App\Models;

class SelectPayment
{
    /**
     * Map a method to its payment‐form URL.
     */
    public function urlFor(int $gigId, string $method, string $couponCode = ''): string
    {
        $qs = http_build_query([
            'gig_id'     => $gigId,
            'coupon_code'=> $couponCode,
        ]);

        return match($method) {
            'card'  => "card.php?{$qs}",
            'bkash' => "bkash.php?{$qs}",
            'nagad' => "nagad.php?{$qs}",
            default => throw new \InvalidArgumentException("Invalid method"),
        };
    }
}
