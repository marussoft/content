<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\ContentField\Actions\ValidateFieldAction;
use Marussia\ContentField\Actions\CreateFieldDataAction;
use Marussia\Content\Content;

class UpdatePageAction
{
    protected $repository;

    protected $validateField;

    protected $createFieldData;

    protected $pageId;

    protected $data = [];

    protected $createFieldData;

    public function __construct(PageRepository $repository, CreateFieldDataAction $createFieldData, ValidateFieldAction $validateField)
    {
        $this->repository = $repository;
        $this->createFieldData = $createFieldData;
        $this->validateField = $validateField;
    }

    public function execute() : Content
    {
        if ($this->pageId === null) {
            throw new PageIdForUpdateNotReceiptedException;
        }

        $page = $this-repository->getPageById($this->pageId);

        $fields = $this->repository->getFields($this->pageId);

        $contentData = [];

        foreach ($this->data as $fieldName => $updateData) {

            if ($fields->has($fieldName)) {
                $fieldData = $this->createFieldData->data($fields->get($fieldName))->execute();
                $fieldData->value = $updateData;
                $contentData[$fieldName] = $this->validateField->fieldData($fieldData)->execute();
                continue;
            }

            $contentData[$fieldName] = $this->createFieldWithoutHandler($fieldName, $updateData);
        }

        $content = $this->contentBuilder->createContent($contentData);

        if ($content->isValid()) {
            $this->repository->updatePage($content);
        }

        return $content;
    }

    public function pageId(int $pageId) : self
    {
        $this->pageId = $pageId;
        return $this;
    }

    public function updates(array $data) : self
    {
        $this->data = $data;
        return $this;
    }
}
