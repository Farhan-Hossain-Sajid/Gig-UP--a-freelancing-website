<?php
namespace App\Controllers;

use App\Models\Rating;
use App\Models\DB;

class RatingController
{
    protected Rating $rating;

    public function __construct()
    {
        $this->rating = new Rating(DB::get());
    }

    public function showForm($orderId)
    {
        require __DIR__ . '/../../app/Views/rating/form.php';
    }

    public function store()
    {
        $this->rating->add(
            (int)$_POST['order_id'],
            $_SESSION['user']['id'],
            (int)$_POST['rating']
        );
        header("Location: /orders/buyer");
        exit;
    }
}