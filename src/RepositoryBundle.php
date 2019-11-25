<?php

declare(strict_types=1);

namespace Marussia\Content;

use Marussia\Content\Repositories\ContentRepository;
use Marussia\Content\Repositories\ContentTypeRepository;

class RepositoryBundle
{
    private $contentRepository;

    private $contentTypeRepository;

    public function __construct(ContentRepository $contentRepository, ContentTypeRepository, $contentTypeRepository)
    {
        $this->contentRepository = $contentRepository;

        $this->contentTypeRepository = $contentTypeRepository;
    }

    public function getFields(string $contentTypeName)
    {
        $fieldsTable = $this->makeFieldsTableName($contentTypeName);
        return $this->contentRepository->getFields($fieldsTable);
    }

    public function getFieldsValues(string $contentTypeName, int $contentId)
    {
        $valuesTable = $this->makeValuesTableName($contentTypeName);
        return $this->contentRepository->getFields($valuesTable);
    }

    protected function makeFieldsTableName(string $contentTypeName) : string
    {
        return $contentType . '_fields';
    }

    protected function makeValuesTableName(string $contentType) : string
    {
        return $contentType . '_field_values';
    }
}
