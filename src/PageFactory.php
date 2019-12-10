<?php

declare(strict_types=1);

namespace Marussia\Content;

use Marussia\Content\Entities\Page;

class PageFactory
{
    public function create(string $name, string $slug, string $title, array $options) : Page
    {
        $page = new Page;

        $page->name = $name;
        $page->slug = $slug;
        $page->title = $title;
        $page->options = json_encode($options, JSON_UNESCAPED_UNICODE);

        return $page;
    }
    
    public function createFromArray(array $data) : Page
    {
        $page = new Page;

        $page->id = $data['id'];
        $page->name = $data['name'];
        $page->slug = $data['slug'];
        $page->title = $data['title'];
        $page->options = json_encode($options, JSON_UNESCAPED_UNICODE);

        return $page;
    }
}
