<?php

namespace App\Controller;

use App\Http\Response;

class MainController extends BaseController
{
    public function index(): Response
    {
        $lastPost = $this->query->findOneBy([], ['DESC', 'id']) ?? '';

        return $this->render('main_index', ['post' => $lastPost]);
    }

    public function contact(): Response
    {
        return $this->render('main_contact');
    }
}
