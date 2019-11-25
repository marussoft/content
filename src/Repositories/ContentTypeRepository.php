<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

class ContentTypeRepository
{
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
