<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Bundles\PageById as Repository;
use Marussia\Content\Actions\Providers\FillFieldProvider as ActionProvider;
use Marussia\Content\Content;
use Marussia\Content\ContentBuilder;

class GetPageByIdAction
{
    protected $repository;

    protected $actionProvider;

    protected $contentBuilder;

    public function __construct(Repository $repository, ActionProvider $actionProvider, ContentBuilder $contentBuilder)
    {
        $this->repository = $repository;
        $this->actionProvider = $actionProvider;
        $this->contentBuilder = $contentBuilder;
    }

    public function execute(int $pageId) : Content
    {
        $page = $this->repository->getPageById($pageId);

        if ($page === null) {
            throw new PageNotFoundException($pageId);
        }

        $fields = $this->repository->getFields($pageId);
        $fieldsValues = $this->repository->getFieldsValuesById($page['pageName'], $contentId, $this->language);

        $generator = (function() use ($fieldsValues) {
            foreach ($fieldsValues as $value) {
                yield from $value;
            }
        })();
        
        $contentData = [];

        foreach ($fieldsValues as $fieldType => $value) {
            if ($fields->has($fieldType)) {
                $fieldData = $this->actionProvider->createFieldData($fields->get($fieldType));
                $fieldData->value = $value;
                $contentData[$fieldType] = $this->actionProvider->fillField($fieldData);
            } else {
                $contentData[$fieldType] = $this->actionProvider->createFieldWithoutHandler($value);
            }
        }

        return $this->contentBuilder->createContent($contentData);
    }
}
 
