<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Entities\Page;

class GetPageByNameAction
{
    private $repository;
    
    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $pageName) : ?Page
    {
        return $this->repository->getPageByName($pageName);
    }
}
