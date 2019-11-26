<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle as Repository;
use Marussia\Content\Actions\Providers\GetContentByIdProvider as ActionProvider;
use Marussia\Content\ViewModels\Content;
use Marussia\Content\ContentBuilder;

class GetContentByIdAction extends AbstractAction
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

    public function execute(string $contentTypeName, int $contentId) : Content
    {
        $contentType = $this->repository->getContentType($contentTypeName);

        if ($contentType === null) {
            throw new ContentTypeNotFoundException($contentTypeName);
        }

        $fields = $this->repository->getFields($contentTypeName);
        $fieldsValues = $this->repository->getFieldsValuesById($contentTypeName, $contentId, $this->language);

        $contentData = [];

        foreach ($fieldsValues as $fieldType => $value) {
            if ($fields->has($fieldType)) {
                $fieldData = $this->actionProvider->createFieldData($fields->get($fieldType));
                $fieldData->value = $value;
                $contentData[$fieldType] = $this->actionProvider->fillField($fieldData);
                continue;
            }
            $contentData[$fieldType] = $this->actionProvider->createFieldWithoutHandler($value);
        }

        return $this->contentBuilder->createContent($contentData);
    }
}
