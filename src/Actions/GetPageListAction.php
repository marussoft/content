<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Collection;

class GetPageListAction
{
    protected $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute() : Collection
    {
        return $this->repository->getAll();
    }
}
