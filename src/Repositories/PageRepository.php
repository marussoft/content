<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

use Marussia\Content\Collection;

class PageRepository
{
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFields($pageId) : Collection
    {
        $sql = 'SELECT * FROM pages_values WHERE page_id = ?';

        $result = $this->pdo->prepare($sql);

        $result->execute([$pageId]);

        $fields = $result->fetchAll(\PDO::FETCH_ASSOC);

        $fieldCollection = new Collection;

        if ($fields !== null) {
            foreach ($fields as $field) {
                $fieldCollection->set($field['type'], $field)
            }
        }

        return $fieldCollection;
    }

    public function getFieldsValuesById(string $pageName, int $pageId, string $language) : Collection
    {
        $valuesTable = $this->makeValuesTableName($pageName);
        $sql = 'SELECT * ' .
               'FROM :page_values_table' .
               'WHERE page_id = :page_id ' .
               'AND language = :language';

        $result = $this->pdo->prepare($sql);

        $result->bindParam(':page_values_table', $valuesTable, \PDO::PARAM_STR);
        $result->bindParam(':page_id', $pageId, \PDO::PARAM_INT);
        $result->bindParam(':language', $language, \PDO::PARAM_STR);

        $result->execute();

        $data = $result->fetch(\PDO::FETCH_ASSOC);

        if ($data === null) {
            return new Collection;
        }

        return new Collection($data);
    }

    public function addPage($page) : bool
    {
        $sql = 'INSERT INTO pages (name, slug, title, options) VALUES (:name, :slug, :title, :options)';

        $result = $this->pdo->prepare($sql);

        $result->bindParam(':name', $page->name, \PDO::PARAM_STR);
        $result->bindParam(':slug', $page->slug, \PDO::PARAM_STR);
        $result->bindParam(':title', $page->title, \PDO::PARAM_STR);
        $result->bindParam(':options', $page->options, \PDO::PARAM_STR);
        return $result->execute();
    }

    protected function makeValuesTableName(string $pageName) : string
    {
        return strtolower('page_' . $pageName . '_fields_values');
    }
}

