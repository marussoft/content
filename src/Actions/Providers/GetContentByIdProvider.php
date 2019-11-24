<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Fields\Actions\FillAction;
use Marussia\Fields\FieldDataFactory;
use Marussia\Fields\Entities\Field;
use Marussia\Fields\FieldData;

class GetContentByIdProvider
{
    protected $fillFieldAction;

    protected $fieldDataFactory;

    public function __construct(FillAction $fillFieldAction, FieldDataFactory $fieldDataFactory)
    {
        $this->fillFieldAction = $fillFieldAction;
        $this->fieldDataFactory = $fieldDataFactory
    }

    public function createFieldData(array $data) : FieldData
    {
        return $this->fillFieldAction->execute($data);
    }

    public function fillField(FieldData $fieldData) : Field
    {
        return $this->fillFieldAction->execute($fieldData);
    }
}
