<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

abstract class AbstractAction
{
    protected $language = 'en-en';

    public function language(string $language) : self
    {
        $this->language = $language;
        return $this;
    }
}
