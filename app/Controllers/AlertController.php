<?php
namespace App\Controllers;

use App\Models\Alert;
use App\Models\DB;

class AlertController
{
    protected Alert $alert;

    public function __construct()
    {
        $this->alert = new Alert(DB::get());
    }

    public function postForm()
    {
        require __DIR__ . '/../../app/Views/alert/post.php';
    }

    public function history()
    {
        $all = $this->alert->all();
        require __DIR__ . '/../../app/Views/alert/post.php';
    }
}