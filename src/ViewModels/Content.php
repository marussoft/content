<?php

declare(strict_types=1);

namespace Marussia\Content\ViewModels;

class Content
{
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
