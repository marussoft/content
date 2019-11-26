<?php

declare(strict_types=1);

namespace Marussia\Content\Repositories;

use Marussia\Content\Entities\ContentType;
use Marussia\Content\ContentTypeFactory;

class ContentTypeRepository
{
    private $pdo;
    
    private $factory;

    public function __construct(\PDO $pdo, ContentTypeFactory $factory)
    {
        $this->pdo = $pdo;
        $this->factory = $factory;
    }
    
    public function get(string $contentTypeName) : ?ContentType
    {
        $sql = 'SELECT * FROM content_types WHERE name = :name';
        
        $this->pdo->prepare($sql);
        
        $result->execute([':name' => $contentTypeName]);
        
        $contentType = null;
        
        $data = $result->fetch(\PDO::FETCH_ASSOC);
        
        if ($data !== null) {
            $contentType = $this->factory->createFromArray($data);
        }
        
        return $contentType;
    }

}
