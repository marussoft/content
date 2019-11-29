<?php

declare(strict_types=1);

namespace Marussia\Content\Actions\Providers;

use Marussia\ContentField\Actions\FillAction;
use Marussia\ContentField\FieldDataFactory;
use Marussia\ContentField\Field;
use Marussia\ContentField\FieldData;

class FillFieldProvider
{
    protected $fillFieldAction;

    protected $fieldDataFactory;

    protected $fieldFactory;

    public function __construct(FillAction $fillFieldAction, FieldDataFactory $fieldDataFactory, FieldFactory $fieldFactory)
    {
        $this->fillFieldAction = $fillFieldAction;
        $this->fieldDataFactory = $fieldDataFactory;
        $this->fieldFactory = $fieldFactory;
    }

    public function createFieldData(array $data) : FieldData
    {
        return $this->fieldDataFactory->create($data);
    }

    public function fillField(FieldData $fieldData) : Field
    {
        return $this->fillFieldAction->execute($fieldData);
    }

    public function createFieldWithoutHandler($value) : Field
    {
        return $this->fieldFactory(['value' => $value]);
    }

}
