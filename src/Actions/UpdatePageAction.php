<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Content;

class UpdatePageAction
{
    protected $repository;

    protected $page = null;

    protected $data = [];

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute()
    {
        if ($page === null) {
            throw new PageForUpdateNotReceivedException;
        }

        $this->repository->updatePage($this->page);
    }

    public function page(Content $page)
    {
        $this->page = $page;
    }

    public function updates(array $data)
    {
        $this->data = $data;
    }
}
