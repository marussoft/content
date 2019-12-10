<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\TableBuilders\PageBuilder;
use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\PageFactory;
use Marussia\Content\Exceptions\CreatePageActionException;
use Marussia\Content\Exceptions\SlugInvalidCharactersException;

class CreatePageAction
{
    private $builder;

    private $repository;

    private $factory;

    private $pageName = '';

    private $slug = '';

    private $title = '';

    private $options = [];

    public function __construct(PageBuilder $builder, PageRepository $repository, PageFactory $factory)
    {
        $this->builder = $builder;
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function execute() : bool
    {
        if (strlen($this->pageName) === 0) {
            throw new PageNameNotSetException;
        }

        if (strlen($this->slug) === 0) {
            throw new SlugNotSetException;
        }

        if (strlen($this->title) === 0) {
            throw new TitleNotSetException;
        }

        try {
            $this->builder->beginTransaction();
            $this->builder->createPageValuesTable($this->pageName);
            $this->builder->commit();
        } catch (\Throwable $exception) {
            $this->builder->rollBack();
            throw new CreatePageActionException($exception);
        }
        $page = $this->factory->create($this->pageName, $this->slug, $this->title, $this->options);
        return $this->repository->addPage($page);
    }

    public function name(string $pageName) : self
    {
//         if (preg_match('/.*/', $pageName)) { // ошибка в регулярке
//             throw new PageNameInvalidCharactersException($pageName);
//         }

        $this->pageName = $pageName;
        return $this;
    }

    public function slug(string $slug) : self
    {
//         if (preg_match('/[^0-9_]/i', $slug)) {
//             throw new SlugInvalidCharactersException($slug);
//         }

        $this->slug = $slug;
        return $this;
    }

    public function title(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    public function options(array $options) : self
    {
        $this->options = $options;
        return $this;
    }
}
