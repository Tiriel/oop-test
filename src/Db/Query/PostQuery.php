<?php

namespace App\Db\Query;

use App\Db\Connection;
use App\Db\Model\Post;
use App\Db\Query\Core\ClassBoundQuery;

class PostQuery extends ClassBoundQuery
{
    public function __construct(Connection $db)
    {
        parent::__construct($db, Post::class);
    }
}
