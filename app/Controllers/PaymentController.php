<?php
namespace App\Controllers;

use App\Models\Payment;
use App\Models\DB;

class PaymentController
{
    protected Payment $payment;

    public function __construct()
    {
        $this->payment = new Payment(DB::get());
    }

    public function selectMethod()
    {
        session_start();
        require __DIR__ . '/../../app/Views/payment/select.php';
    }

    public function showBkash()
    {
        require __DIR__ . '/../../app/Views/payment/bkash.php';
    }

    public function showCard()
    {
        require __DIR__ . '/../../app/Views/payment/card.php';
    }

    public function showNagad()
    {
        require __DIR__ . '/../../app/Views/payment/nagad.php';
    }

    public function process()
    {
        $this->payment->process(
            $_POST['gig_id'],
            $_POST['method'],
            $_SESSION['user']['id'],
            $_POST
        );
        require __DIR__ . '/../../app/Views/payment/success.php';
    }
}