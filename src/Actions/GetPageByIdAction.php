<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Actions\Providers\FillFieldProvider as ActionProvider;
use Marussia\Content\Content;
use Marussia\Content\ContentBuilder;

class GetPageByIdAction extends AbstractAction
{
    protected $repository;

    protected $actionProvider;

    protected $contentBuilder;

    public function __construct(PageRepository $repository, ActionProvider $actionProvider, ContentBuilder $contentBuilder)
    {
        $this->repository = $repository;
        $this->actionProvider = $actionProvider;
        $this->contentBuilder = $contentBuilder;
    }

    public function execute(int $pageId) : ?Content
    {
        $page = $this->repository->getPageById($pageId);

        if ($page === null) {
            return $page;
        }

        $fields = $this->repository->getFields($page->id);
        $fieldsValues = $this->repository->getFieldsValues($page->name, $this->language);

        $contentData = [];

        foreach ($fieldsValues as $fieldName => $value) {

            if ($fields->has($fieldName)) {
                $fieldData = $this->actionProvider->createFieldData($fields->get($fieldName));
                $fieldData->value = $value;
                $contentData[$fieldName] = $this->actionProvider->fillField($fieldData);
                continue;
            }
            $contentData[$fieldName] = $this->actionProvider->createFieldWithoutHandler($fieldName, $value);
        }

        $contentData['id'] = $page->id;
        $contentData['name'] = $page->name;
        $contentData['slug'] = $page->slug;
        $contentData['title'] = $page->title;
        $contentData['options'] = $page->options;

        return $this->contentBuilder->createContent($contentData);
    }
}
