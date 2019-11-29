<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle;
use Marussia\Content\Actions\Providers\FillFieldProvider as ActionProvider;
use Marussia\Content\ViewModels\ContentList;
use Marussia\Content\ContentBuilder;

class GetContentListAction extends AbstractAction
{
    protected $repository;

    protected $actionProvider;

    protected $contentBuilder;
    
    protected $filter;
    
    protected $sort;

    public function __construct(RepositoryBundle $repository, FillFieldProvider $actionProvider, ContentBuilder $contentBuilder)
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

        $fields = $this->repository->getFields($contentTypeName);
        $fieldsValues = $this->repository->getFieldsValuesList($contentType, $this->filter, $this->sort, $this->language);
        
        $generator = (function() use ($fieldsValues) {
            foreach ($fieldsValues as $value) {
                yield from $value;
            }
        })();
        
        $contentData = [];
        
        foreach ($generator as $fieldType => $value) {
            if ($fields->has($fieldType)) {
                $fieldData = $this->actionProvider->createFieldData($fields->get($fieldType));
                $fieldData->value = $value;
                $contentData[$fieldType] = $this->actionProvider->fillField($fieldData);
                continue;
            }
            $contentData[$fieldType] = $this->actionProvider->createFieldWithoutHandler($value);
        }
    }
    
    public function filter(array $filter) : self
    {
        $this->filter = $filter;
        return $this;
    }
    
    public function sort(array $sort) : self
    {
        $this->sort = $sort;
        return $this;
    }
}
