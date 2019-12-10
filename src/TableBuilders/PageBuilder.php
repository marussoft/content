<?php

declare(strict_types=1);

namespace Marussia\Content\TableBuilders;

class PageBuilder
{
    use NameBuilderTrait;

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function beginTransaction() : self
    {
        $this->pdo->beginTransaction();
        return $this;
    }

    public function commit() : self
    {
        $this->pdo->commit();
        return $this;
    }

    public function rollBack() : void
    {
        $this->pdo->rollBack();
    }

    public function createPageValuesTable(string $pageName)
    {
        $valuesTableName = $this->makeValuesTableName($pageName);

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $valuesTableName . '(' .
            'id SERIAL PRIMARY KEY, ' .
            'language VARCHAR(10) NOT NULL)';

        $result = $this->pdo->prepare($sql);

//         $result->bindParam(':page_values_table', $valuesTableName, \PDO::PARAM_STR);

        $result->execute();
    }

    public function createPagesTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS pages (' .
            'id SERIAL PRIMARY KEY, ' .
            'name VARCHAR(255) NOT NULL UNIQUE, ' .
            'slug VARCHAR(255) NOT NULL, ' .
            'title VARCHAR(255) NOT NULL, ' .
            'options JSONB, ' .
            'is_active BOOLEAN DEFAULT TRUE, ' .
            'created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(), ' .
            'updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW())';

        $this->pdo->exec($sql);
    }

    public function createFieldsTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS pages_fields (' .
            'id SERIAL PRIMARY KEY, ' .
            'page_id SERIAL, ' .
            'name VARCHAR(255) NOT NULL UNIQUE, ' .
            'type VARCHAR(255) NOT NULL, ' .
            'title VARCHAR(255) NOT NULL, ' .
            'options JSONB, ' .
            'is_active BOOLEAN DEFAULT TRUE, ' .
            'hidden BOOLEAN DEFAULT FALSE)';

        $this->pdo->exec($sql);
    }
}
