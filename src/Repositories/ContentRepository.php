<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

class ContentRepository
{
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
} 
