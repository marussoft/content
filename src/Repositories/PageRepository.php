<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

use Marussia\Content\Content;
use Marussia\Content\Collection;
use Marussia\Content\PageFactory;
use Marussia\Content\Entities\Page;
use Marussia\Content\TableBuilders\NameBuilderTrait;

class PageRepository
{
    use NameBuilderTrait;

    private $pdo;

    private $pageFactory;

    public function __construct(\PDO $pdo, PageFactory $pageFactory)
    {
        $this->pdo = $pdo;
        $this->pageFactory = $pageFactory;
    }

    public function getPageById(int $pageId) : ?Page
    {
        $sql = 'SELECT * FROM pages WHERE id = ?';

        $result = $this->pdo->prepare($sql);

        $result->execute([$pageId]);

        $pageData = $result->fetch(\PDO::FETCH_ASSOC);

        if ($pageData === null) {
            return null;
        }

        return $this->pageFactory->createFromArray($pageData);
    }

    public function getPageBySlug(string $pageSlug) : ?Page
    {
        $sql = 'SELECT * FROM pages WHERE slug = :slug';

        $result = $this->pdo->prepare($sql);

        $result->bindParam(':name', $pageSlug, \PDO::PARAM_STR);

        $result->execute();

        $pageData = $result->fetch(\PDO::FETCH_ASSOC);

        $page = null;

        if ($pageData === false) {
            return $page;
        }

        $page = $this->pageFactory->createFromArray($pageData);

        return $page;
    }

    public function getFields(int $pageId) : Collection
    {
        $sql = 'SELECT * FROM pages_fields WHERE page_id = ?';

        $result = $this->pdo->prepare($sql);

        $result->execute([$pageId]);

        $fields = $result->fetchAll(\PDO::FETCH_ASSOC);

        $fieldCollection = new Collection;

        if ($fields !== null) {
            foreach ($fields as $field) {
                $fieldCollection->set($field['name'], $field);
            }
        }

        return $fieldCollection;
    }

    public function getFieldsValues(string $pageName, string $language) : Collection
    {
        $valuesTable = $this->makeValuesTableName($pageName);
        $sql = 'SELECT * FROM ' . $valuesTable . ' ' .
               'WHERE language = :language';

        $result = $this->pdo->prepare($sql);

        $result->bindParam(':language', $language, \PDO::PARAM_STR);

        $result->execute();

        $data = $result->fetch(\PDO::FETCH_ASSOC);

        if ($data === null) {
            return new Collection;
        }

        return new Collection($data);
    }

    public function addPage(Page $page) : bool
    {
        $sql = 'INSERT INTO pages (name, slug, title, options) VALUES (:name, :slug, :title, :options)';

        $result = $this->pdo->prepare($sql);

        $result->bindParam(':name', $page->name, \PDO::PARAM_STR);
        $result->bindParam(':slug', $page->slug, \PDO::PARAM_STR);
        $result->bindParam(':title', $page->title, \PDO::PARAM_STR);
        $result->bindParam(':options', $page->options, \PDO::PARAM_STR);
        return $result->execute();
    }

    public function addFieldsValues(string $pageName, Content $content) : Content
    {
        $columns = '';
        $values = '';

        foreach ($content as $fieldName => $value) {
            $columns .= $fieldName . ', ';
            $values .= ':' . $fieldName . ', ';
        }

        $valuesTable = $this->makeValuesTableName($pageName);
        $sql = 'INSERT INTO ' . $valuesTable . ' (' . substr($columns,0,-2)  . ') VALUES (' . substr($values,0,-2) . ')';

        $result = $this->pdo->prepare($sql);

        $type = \PDO::PARAM_STR;

        foreach ($content as $key => &$value) {

            if (is_int($value)) {
                $type = \PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = \PDO::PARAM_BOOL;
            }

            $result->bindParam(':' . $key, $value, $type);
        }
        $content->id  = $result->execute();
        return $content;
    }
}
