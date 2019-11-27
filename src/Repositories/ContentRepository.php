<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

use Marussia\Content\Collection;

class ContentRepository
{
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    private function getFields($contentTypeName) : Collection
    {
        $fieldsTable = $this->makeFieldsTableName($contentTypeName);
        $sql = 'SELECT * FROM ?';
        
        $result = $this->pdo->prepare($sql);
        
        $result->execute([$fieldsTable]);
        
        $fields = $result->fetchAll(\PDO::FETCH_ASSOC);
        
        $fieldCollection = new Collection;
        
        if ($fields !== null) {
            foreach ($fields as $field) {
                $fieldCollection->set($field['type'], $field)
            }
        }
        
        return $fieldCollection;
    }
    
    public function getFieldsValuesById(string $contentTypeName, int $contentId, string $language)
    {
        $contentTableName = strtolower($contentTypeName);
        $valuesTable = $this->makeValuesTableName($contentTypeName);
        $sql = 'SELECT content.* ' .
               'FROM :content_table content ' .
               'JOIN :values_table values ' .
               'ON values.content_id = content.id ' .
               'WHERE content.id = :content_id ' .
               'AND language = :language';
               
        $result = $this->pdo->prepare($sql);

        $result->bindParam(':content_table', $contentTableName, \PDO::PARAM_STR);
        $result->bindParam(':values_table', $valuesTable, \PDO::PARAM_STR);
        $result->bindParam(':content_id', $contentId, \PDO::PARAM_INT);
        $result->bindParam(':language', $language, \PDO::PARAM_STR);

        $result->execute();
        
        $data = $result->fetch(\PDO::FETCH_ASSOC);
        
        if ($data !== null) {
            return new Collection($data);
        }
        
        return new Collection;
    }

    protected function makeFieldsTableName(string $contentTypeName) : string
    {
        return strtolower($contentTypeName . '_fields');
    }

    protected function makeValuesTableName(string $contentTypeName) : string
    {
        return strtolower($contentTypeName . '_field_values');
    }

}
