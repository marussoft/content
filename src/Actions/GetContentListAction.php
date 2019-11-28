<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle;
use Marussia\Content\Actions\Providers\GetContentListProvider;
use Marussia\Content\ViewModels\ContentList;
use Marussia\Content\ContentBuilder;

class GetContentListAction extends AbstractAction
{
    protected $repository;

    protected $actionProvider;

    protected $contentBuilder;

    public function __construct(RepositoryBundle $repository, GetContentListProvider $actionProvider, ContentBuilder $contentBuilder)
    {
        $this->repository = $repository;
        $this->actionProvider = $actionProvider;
        $this->contentBuilder = $contentBuilder;
    }
    
    public function execute(string $contentTypeName) : ContentList
    {
        $contentType = $this->repository->getContentType($contentTypeName);

        if ($contentType === null) {
            throw new ContentTypeNotFoundException($contentTypeName);
        }
        
        $options = $contentType->options;
        $fields = $this->repository->getFields($contentTypeName);
        $fieldsValues = $this->repository->getFieldsValuesList($contentTypeName, $options['pagination'], $this->language);

        $contentData = [];
        
        foreach () {}
    }
    
    protected function filter(array $filter) : self
    {
        $this->filter = $filter;
        return $this;
    }
}
