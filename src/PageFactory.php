<?php

declare(strict_types=1);

namespace Marussia\Content;

use Marussia\Content\Entities\Page;

class PageFactory
{
    public function createFromArray(string $name, string $slug, string $title, array $options) : Page
    {
        $page = new Page;

        $page->name = $data['name'];
        $page->title = $data['title'];
        $page->options = json_encode($options, JSON_UNESCAPED_UNICODE);

        return $page;
    }
}
