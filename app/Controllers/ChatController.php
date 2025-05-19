<?php
namespace App\Controllers;

use App\Models\Message;
use App\Models\DB;

class ChatController
{
    protected Message $msg;

    public function __construct()
    {
        $this->msg = new Message(DB::get());
    }

    public function index($orderId)
    {
        session_start();
        $convo = $this->msg->forOrder((int)$orderId);
        require __DIR__ . '/../../app/Views/chat/index.php';
    }

    public function send()
    {
        $this->msg->send(
            $_POST['order_id'],
            $_SESSION['user']['id'],
            $_POST['message']
        );
        header("Location: /chat/" . $_POST['order_id']);
        exit;
    }
}