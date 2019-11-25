<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle;
use Marussia\Content\Actions\Providers\GetContentByIdProvider;
use Marussia\Content\ViewModels\Content;
use Marussia\Content\ContentBuilder;

class GetContentByIdAction
{
    protected $repositoryBundle;

    protected $getContentByIdProvider;

    protected $contentBuilder;

    public function __construct(RepositoryBundle $repositoryBundle, GetContentByIdProvider $getContentByIdProvider, ContentBuilder $contentBuilder)
    {
        $this->repositoryBundle = $repositoryBundle;
        $this->getContentByIdProvider = $getContentByIdProvider;
        $this->contentBuilder = $contentBuilder;
    }

    public function execute(string $contentTypeName, int $contentId) : Content
    {
        $contentType = $this->repositoryBundle->getContentType($contentTypeName);

        if ($contentType === null) {
            throw new ContentTypeNotFoundException($contentTypeName);
        }

        $fields = $this->repositoryBundle->getFields($contentTypeName);
        $fieldsValues = $this->repositoryBundle->getFieldsValues($contentTypeName, $contentId);

        $contentData = [];

        foreach ($fieldsValues as $fieldType => $value) {
            if ($fields->has($fieldType)) {
                $fieldData = $this->getContentByIdProvider->createFieldData($fields->get($fieldType));
                $fieldData->value = $value;
                $contentData[$fieldType] = $this->getContentByIdProvider->fillField($fieldData);
                continue;
            }
            $contentData[$fieldType] = $this->getContentByIdProvider->createFieldWithoutHandler($value);
        }

        return $this->contentBuilder->createContent($contentData);
    }
}
