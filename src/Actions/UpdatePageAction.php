<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Contracts\ActionInterface;
use Marussia\Content\Repositories\PageRepository;
use Marussia\ContentField\Actions\GetFieldWithoutHandler;
use Marussia\ContentField\Actions\CreateFieldDataAction;
use Marussia\Content\Content;
use Marussia\Content\ContentBuilder;

class UpdatePageAction extends AbstractAction implements ActionInterface
{
    protected $repository;

    protected $getFieldWithoutHandler;

    protected $createFieldData;
    
    protected $contentBuilder;

    protected $pageId;

    protected $data = [];

    public function __construct(PageRepository $repository, CreateFieldDataAction $createFieldData, GetFieldWithoutHandler $getFieldWithoutHandler, ContentBuilder $contentBuilder)
    {
        $this->repository = $repository;
        $this->createFieldData = $createFieldData;
        $this->getFieldWithoutHandler = $getFieldWithoutHandler;
        $this->contentBuilder = $contentBuilder;
    }

    public function execute() : Content
    {
        if ($this->page === null) {
            throw new PageIdForUpdateNotReceiptedException;
        }

        $fields = $this->repository->getFields($this->page->id);

        $contentData = [];

        foreach ($this->data as $fieldName => $updateData) {

            if (property_exists($this->page, $fieldName) === false) {
                unset($this->data[$fieldName]);
                continue;
            }
        
            if ($fields->has($fieldName)) {
                $fieldData = $this->createFieldData->data($fields->get($fieldName))->execute();
                $fieldData->value = $updateData;
                $contentValues[$fieldName] = $this->createFieldInput->fieldData($fieldData)->execute();
                continue;
            }

            $contentValues[$fieldName] = $this->getFieldWithoutHandler->value($updateData);
        }

        $content = $this->contentBuilder->createContent($contentData);
        
        if ($content->isValid() && count($this->data) > 0) {
            $this->repository->updatePageValues($this->page->name, $this->data, $this->page->language->value);
        }
        
        return $content;
    }

    public function page(Content $page) : self
    {
        $this->page = $page;
        return $this;
    }

    public function update(array $data) : self
    {
        $this->data = $data;
        return $this;
    }
}
