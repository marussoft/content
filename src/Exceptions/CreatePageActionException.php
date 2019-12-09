<?php

declare(strict_types=1);

namespace Marussia\Content\Exceptions;

class CreatePageActionException extendds \Exception
{
    public function __construct(\Throwable $exception)
    {
        $error = 'Create page error: ' . $exception->getTraceAsString() . ' in line ' . $exception->getLine();
        parent::__construct($error);
    }
}
