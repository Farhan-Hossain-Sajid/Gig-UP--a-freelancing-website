<?php
namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Show login / landing page
        require __DIR__ . '/../../app/Views/home/index.php';
    }
}