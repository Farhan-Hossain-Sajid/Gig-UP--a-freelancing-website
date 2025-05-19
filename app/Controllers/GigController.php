<?php
namespace App\Controllers;

use App\Models\Gig;
use App\Models\DB;

class GigController

    protected Gig $gig;

    public function __construct()
    {
        $this->gig = new Gig(DB::get());
    }

    // GET /gigs
    public function index()
    {
        $filters = [
            'category'    => $_GET['category'] ?? null,
            'min_price'   => $_GET['min_price'] ?? null,
            'max_price'   => $_GET['max_price'] ?? null,
            'max_delivery'=> $_GET['max_delivery'] ?? null,
            'title'       => $_GET['title'] ?? null,
        ];
        $gigs = $this->gig->all(array_filter($filters));
        require __DIR__ . '/../../app/Views/gig/index.php';
    }

    // GET /gigs/{id}
    public function show($id)
    {
        $gig = $this->gig->find((int)$id);
        if (!$gig) {
            http_response_code(404);
            echo "Gig not found.";
            exit;
        }
        require __DIR__ . '/../../app/Views/gig/show.php';
    }

    // GET /gigs/create
    public function create()
    {
        require __DIR__ . '/../../app/Views/gig/create.php';
    }

    // POST /gigs
    public function store()
    {
        $data = [
            'seller_id'     => $_SESSION['user']['id'],
            'title'         => trim($_POST['title']),
            'description'   => trim($_POST['description']),
            'category'      => trim($_POST['category']),
            'price'         => (float)$_POST['price'],
            'delivery_days' => (int)$_POST['delivery_days'],
        ];
        $this->gig->create($data);
        header('Location: /gigs');
        exit;
    }
