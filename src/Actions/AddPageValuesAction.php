<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Content;

class AddPageValuesAction
{
    private $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $pageName, Content $content) : Content
    {
        return $this->repository->addFieldsValues($pageName, $content);
    }
}
