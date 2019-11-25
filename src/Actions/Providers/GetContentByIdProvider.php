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
