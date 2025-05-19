<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\DB;

class OrderController
{
    protected Order $order;

    public function __construct()
    {
        $this->order = new Order(DB::get());
    }

    public function buyerOrders()
    {
        session_start();
        $orders = $this->order->forBuyer($_SESSION['user']['id']);
        require __DIR__ . '/../../app/Views/dashboard/buyer_orders.php';
    }

    public function sellerOrders()
    {
        session_start();
        $orders = $this->order->forSeller($_SESSION['user']['id']);
        require __DIR__ . '/../../app/Views/dashboard/seller_orders.php';
    }

    public function complete()
    {
        $this->order->complete((int)$_POST['order_id']);
        header("Location: /orders/seller");
        exit;
    }
}