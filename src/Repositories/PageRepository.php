<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

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
        $sql = 'SELECT * FROM pages WHERE page_id = ?';

        $result = $this->pdo->prepare($sql);

        $result->execute([$pageId]);

        $pageData = $result->fetchAll(\PDO::FETCH_ASSOC);
        
        if ($pageData === null) {
            return null;
        }

        return $this->pageFactory->createFromArray($pageData);
    }

    public function getPageByName(string $pageName) : ?Page
    {
        $sql = 'SELECT * FROM pages WHERE name = :name';
        
        $result = $this->pdo->prepare($sql);
        
        $result->bindParam(':name', $pageName, \PDO::PARAM_STR);
        
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
                $fieldCollection->set($field['type'], $field);
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
}

