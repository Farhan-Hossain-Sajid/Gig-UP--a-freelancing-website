<?php
namespace App\Controllers;

use App\Models\Referral;
use App\Models\DB;

class ReferralController
{
    protected Referral $ref;

    public function __construct()
    {
        $this->ref = new Referral(DB::get());
    }

    public function index()
    {
        session_start();
        $history = $this->ref->forUser($_SESSION['user']['id']);
        require __DIR__ . '/../../app/Views/refer/index.php';
    }
}