<?php
namespace App\Controllers;

use App\Models\Coupon;
use App\Models\DB;

class CouponController
{
    protected Coupon $coupon;

    public function __construct()
    {
        $this->coupon = new Coupon(DB::get());
    }

    public function create()
    {
        require __DIR__ . '/../../app/Views/coupon/create.php';
    }

    public function history()
    {
        $all = $this->coupon->all();
        require __DIR__ . '/../../app/Views/coupon/create.php';
    }
}