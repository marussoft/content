<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\TableBuilders\PageBuilder;

class CreatePageFieldValueAction
{
    private $builder;
    
    public function __construct(PageBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function execute(string $pageName, string $fieldName)
    {
        $this->builder->createFieldValue();
    }
}
