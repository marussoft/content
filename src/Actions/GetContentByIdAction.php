<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle;
use Marussia\Fields\Actions\FillAction;
use Marussia\Fields\FieldDataFactory;

class GetContentByIdAction
{
    protected $repositoryBundle;

    protected $fillFieldAction;

    protected $fieldDataFactory;

    public function __construct(RepositoryBundle $repositoryBundle, FillAction $fillFieldAction, FieldDataFactory $fieldDataFactory)
    {
        $this->repositoryBundle = $repositoryBundle;
        $this->fillFieldAction = $fillAction;
        $this->fieldDataFactory = $fieldDataFactory
    }

    public function execute(string $contentType, int $contentId)
    {
        $contentType = $this->repositoryBundle->getContentType($contentType);

        if ($contentType === null) {
            throw new ContentTypeNotFoundException($contentType);
        }

        $fieldsTable = $this->makeFieldsTableName($contentType);
        $valuesTable = $this->makeValuesTableName($contentType);

        $fields = $this->repositoryBundle->getFields($fieldsTable);
        $fieldsValues = $this->repositoryBundle->getFieldsValues($valuesTable, $contentId);

        $content = [];

        foreach ($fieldsValues as $fieldType => $value) {
            if (array_key_exists($fieldType, $fields)) {
                $fieldData = $this->fieldDataFactory->create($fields[$fieldType]);
                $content[$fieldType] = $this->fillFieldAction->execute($fieldData);
            }
        }
    }

    protected function makeFieldsTableName() : string
    {
        return $contentType . '_fields';
    }

    protected function makeValuesTableName(string $contentType) : string
    {
        return $contentType . '_field_values';
    }
}
