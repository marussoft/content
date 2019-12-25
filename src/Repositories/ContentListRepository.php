<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

use Marussia\Content\Collection;
use Marussia\Content\Repositories\QueryChunks\Filter;
use Marussia\Content\Repositories\QueryChunks\Sort;

class ContentListRepository
{
    private $filter;
    
    private $sort;
    
    private $pdo;

    public function __construct(Filter $filter, Sort $sort, \PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}
