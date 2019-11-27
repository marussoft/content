<?php

declare(strict_types=1);

namespace Marussia\Content\Entities;

class ContentType
{
    public $id;

    public $name;

    public $title;

    public $description = '';

    public $options = [];
}
