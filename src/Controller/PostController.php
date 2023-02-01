<?php

namespace App\Controller;

use App\Http\Response;

class PostController extends BaseController
{
    public function list(): Response
    {
        $posts = $this->query->findAll();

        return $this->render('post_list', ['posts' => $posts]);
    }

    public function new(): Response
    {
        return $this->render('post_new');
    }
}
