<?php
namespace App\Controllers;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\DB;

class AdminController
{
    public function index()
    {
        $txns = (new Transaction(DB::get()))->all();
        require __DIR__ . '/../../app/Views/admin/dashboard.php';
    }

    public function complaints()
    {
        $all = (new Complaint(DB::get()))->all();
        require __DIR__ . '/../../app/Views/admin/complaints.php';
    }

    public function topSellers()
    {
        $tops = (new Rating(DB::get()))->topRanked(4.8);
        require __DIR__ . '/../../app/Views/admin/top_sellers.php';
    }

    public function sellerDetails($id)
    {
        $details = (new Rating(DB::get()))->details((int)$id);
        require __DIR__ . '/../../app/Views/admin/seller_details.php';
    }

    public function bonusTxns()
    {
        $bonuses = (new Transaction(DB::get()))->bonuses();
        require __DIR__ . '/../../app/Views/admin/bonus_transactions.php';
    }
}