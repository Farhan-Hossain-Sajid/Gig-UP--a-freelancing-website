<?php
namespace App\Controllers;

use App\Models\Complaint;
use App\Models\DB;

class ComplaintController
{
    protected Complaint $complaint;

    public function __construct()
    {
        $this->complaint = new Complaint(DB::get());
    }

    public function showForm()
    {
        require __DIR__ . '/../../app/Views/complaint/create.php';
    }

    public function store()
    {
        $this->complaint->lodge(
            $_POST['order_id'],
            $_SESSION['user']['id'],
            $_POST['message']
        );
        header("Location: /complaints/my");
        exit;
    }

    public function myList()
    {
        $list = $this->complaint->forUser($_SESSION['user']['id']);
        require __DIR__ . '/../../app/Views/complaint/my_list.php';
    }

    public function adminList()
    {
        $all = $this->complaint->all();
        require __DIR__ . '/../../app/Views/complaint/admin_list.php';
    }
}