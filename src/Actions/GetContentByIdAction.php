<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle;
use Marussia\Content\Actions\Providers\GetContentByIdProvider;
use Marussia\Content\ViewModels\Content;

class GetContentByIdAction
{
    protected $repositoryBundle;

    protected $getContentByIdProvider;

    protected $content;

    public function __construct(RepositoryBundle $repositoryBundle, GetContentByIdProvider $getContentByIdProvider, Content $content)
    {
        $this->repositoryBundle = $repositoryBundle;
        $this->getContentByIdProvider = $getContentByIdProvider;
        $this->content = $content;
    }

    public function execute(string $contentTypeName, int $contentId) : Content
    {
        $contentType = $this->repositoryBundle->getContentType($contentTypeName);

        if ($contentType === null) {
            throw new ContentTypeNotFoundException($contentTypeName);
        }

        $fields = $this->repositoryBundle->getFields($contentTypeName);
        $fieldsValues = $this->repositoryBundle->getFieldsValues($contentTypeName, $contentId);

        $content = [];

        foreach ($fieldsValues as $fieldType => $value) {
            if (array_key_exists($fieldType, $fields)) {
                $fieldData = $this->getContentByIdProvider->createFieldData($fields[$fieldType]);
                $content[$fieldType] = $this->getContentByIdProvider->fillField($fieldData);
            }
        }

        $this->content->setData($content);

        return $this->content;
    }
}
