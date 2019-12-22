<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\Repositories\PageRepository;
use Marussia\ContentField\Actions\CreateFieldInputAction;
use Marussia\ContentField\Actions\CreateFieldDataAction;
use Marussia\Content\Content;

class UpdatePageAction extends AbstractAction
{
    protected $repository;

    protected $createInput;

    protected $createFieldData;

    protected $pageId;

    protected $data = [];

    public function __construct(PageRepository $repository, CreateFieldDataAction $createFieldData, CreateFieldInputAction $createInput)
    {
        $this->repository = $repository;
        $this->createFieldData = $createFieldData;
        $this->createInput = $createInput;
    }

    public function execute() : Content
    {
        if ($this->pageId === null) {
            throw new PageIdForUpdateNotReceiptedException;
        }

        $page = $this->repository->getPageById($this->pageId);

        $fields = $this->repository->getFields($this->pageId);

        $contentData = [];

        foreach ($this->data as $fieldName => $updateData) {

            if ($fields->has($fieldName)) {
                $fieldData = $this->createFieldData->data($fields->get($fieldName))->execute();
                $fieldData->value = $updateData;
                $contentValues[$fieldName] = $this->createFieldInput->fieldData($fieldData)->execute();
                continue;
            }

            $contentValues[$fieldName] = $this->createFieldWithoutHandler($fieldName, $updateData);
        }

        $content = $this->contentBuilder->createContent($contentData);



        if ($content->isValid()) {
            $this->repository->updatePage($this->data);
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
