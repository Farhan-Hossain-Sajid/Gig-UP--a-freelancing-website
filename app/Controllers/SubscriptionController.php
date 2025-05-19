<?php
namespace App\Controllers;

use App\Models\Subscription;
use App\Models\DB;

class SubscriptionController
{
    protected Subscription $sub;

    public function __construct()
    {
        $this->sub = new Subscription(DB::get());
    }

    public function index()
    {
        session_start();
        require __DIR__ . '/../../app/Views/subscription/index.php';
    }

    public function subscribe()
    {
        $this->sub->create($_SESSION['user']['id'], $_POST);
        header("Location: /subscription");
        exit;
    }

    public function unsubscribe()
    {
        $this->sub->cancel($_POST['sub_id']);
        header("Location: /subscription");
        exit;
    }
}