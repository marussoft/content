<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\Content\Entities\Page;

class GetPageBySlugAction extends AbstractAction
{
    private $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $pageSlug) : ?Page
    {
        $page = $this->repository->getPageBySlug($pageSlug);

        if ($page === null) {
            throw new PageNotFoundException($pageSlug);
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
            $contentData[$fieldName] = $this->actionProvider->createFieldWithoutHandler($value);
        }

        $contentData['id'] = $page->id;
        $contentData['name'] = $page->name;
        $contentData['slug'] = $page->slug;
        $contentData['title'] = $page->title;
        $contentData['options'] = $page->options;

        return $this->contentBuilder->createContent($contentData);
    }
}
