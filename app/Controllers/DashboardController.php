<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\DB;

class DashboardController
{
    public function buyer()
    {
        session_start();
        require __DIR__ . '/../../app/Views/dashboard/buyer.php';
    }

    public function seller()
    {
        session_start();
        require __DIR__ . '/../../app/Views/dashboard/seller.php';
    }

    public function myRatings()
    {
        $ratings = (new Rating(DB::get()))->averageForSeller($_SESSION['user']['id']);
        require __DIR__ . '/../../app/Views/dashboard/my_ratings.php';
    }

    public function myBonuses()
    {
        $bonuses = (new Transaction(DB::get()))->bonusesForSeller($_SESSION['user']['id']);
        require __DIR__ . '/../../app/Views/dashboard/my_bonuses.php';
    }
}