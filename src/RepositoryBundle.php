<?php

declare(strict_types=1);

namespace Marussia\Content;

use Marussia\Content\Repositories\ContentRepository;
use Marussia\Content\Repositories\ContentTypeRepository;
use Marussia\Content\Entities\ContentType;

class RepositoryBundle
{
    private $contentRepository;

    private $contentTypeRepository;

    public function __construct(ContentRepository $contentRepository, ContentTypeRepository, $contentTypeRepository)
    {
        $this->contentRepository = $contentRepository;

        $this->contentTypeRepository = $contentTypeRepository;
    }

    public function getContentType(string $contentTypeName) : ?ContentType
    {
        return $this->contentTypeRepository->get($contentTypeName);
    }

    public function getFields(string $contentTypeName) : Collection
    {
        return $this->contentRepository->getFields($contentTypeName);
    }

    public function getFieldsValuesById(string $contentTypeName, int $contentId, string $language)
    {
        return $this->contentRepository->getFieldsValuesById($contentTypeName, $contentId, $language);
    }
}
