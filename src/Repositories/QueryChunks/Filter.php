<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories\QueqyChunks;

use Marussia\ContentField\Actions\GetFilterParamsAction;

class Filter
{
    private $getFilterParams;
    
    public function __construct(GetFilterParamsAction $getFilterParams)
    {
        $this->getFilterParams = $getFilterParams;
    }

    public function getQueryString(array $params) : string
    {
        $fieldFilterParams = [];
    
        foreach ($params as $filterKey => $filterValue) {
            $fieldFilterParams[$filterKey] = $this->getFilterParams->execute($filterKey, $filterValue);
        }
    }
}
