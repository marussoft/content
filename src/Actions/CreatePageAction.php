<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\TableBuilders\PageBuilder;
use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Exceptions\CreatePageActionException;

class CreatePageAction
{
    private $builder;
    
    private $slug = '';
    
    private $options = [];

    public function __construct(PageBuilder $builder, PageRepository $pageRepository)
    {
        $this->builder = $builder;
        $this->pageRepository = $pageRepository;
    }

    public function execute(string $pageName, string $title)
    {
        try {
            $this->builder->beginTransaction();
            $this->builder->createPagesTable();
            $this->builder->createFieldsTable();
            
            $name = strtolower($pageName);
            $slug = strlen($this->slug) === 0 ? $name : $this->slug;
            $options = json_encode($this->options, JSON_UNESCAPED_UNICODE);
            
            $this->builder->createPageValuesTable($name);
            $this->builder->commit();
        } catch (\Throwable $exception) {
            $this->builder->rollBack();
            throw new CreatePageActionException($exception);
        }
        
        $this->pageRepository->addPage($name, $slug, $title, $options);
    }
    
    public function slug(string $slug) : self
    {
        $this->slug = $slug;
        return $this;
    }

    public function options(array $options) : self
    {
        $this->options = $options;
        return $this;
    }
}
