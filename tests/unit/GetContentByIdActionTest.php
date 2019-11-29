<?php

declare(strict_types=1);

namespace Marussia\Content\Test;

use Marussia\Content\Actions\GetContentByIdAction;
use Marussia\Content\RepositoryBundle;
use Marussia\Content\Actions\Providers\FillFieldProvider;
use Marussia\Content\ViewModels\Content;
use Marussia\Content\ContentBuilder;
use Marussia\Content\Entities\ContentType;
use Marussia\Content\Collection;
use Marussia\ContentField\Field;
use Marussia\ContentField\FieldData;
use PHPUnit\Framework\TestCase;
use \Mockery;

class GetContentByIdActionTest extends TestCase
{
    public function testExecute()
    {
        $repositoryBundle = $this->repositoryBundle();
        $actionProvider = $this->actionProvider();
        $contentBuilder = $this->contentBuilder();
        
        $action = new GetContentByIdAction($repositoryBundle, $actionProvider, $contentBuilder);
        
        $contentTypeName = 'test';
        $contentId = 1;
        
        $this->assertInstanceOf(Content::class, $action->execute($contentTypeName, $contentId));
    }
    
    private function actionProvider() : FillFieldProvider
    {
        $fieldData = Mockery::mock(FieldData::class);
        $field = Mockery::mock(Field::class);
        
        $actionProvider = Mockery::mock(FillFieldProvider::class);
        $actionProvider->shouldReceive([
            'createFieldData' => $fieldData,
            'fillField' => $field,
            'createFieldWithoutHandler' => $field
        ]);
        return $actionProvider;
    }

    private function repositoryBundle() : RepositoryBundle
    {
        $contentType = Mockery::mock(ContentType::class);
        
        $fieldCollection = Mockery::mock(Collection::class);
        $fieldCollection->shouldReceive([
            'has' => true,
            'get' => []
        ]);
        
        $iterator = new \ArrayIterator(['test' => '']);
        
        $fieldValueCollection = Mockery::mock(Collection::class);
        $fieldValueCollection->shouldReceive(['getIterator' => $iterator]);
        
        $repositoryBundle = Mockery::mock(RepositoryBundle::class);
        $repositoryBundle->shouldReceive([
            'getContentType' => $contentType,
            'getFields' => $fieldCollection,
            'getFieldsValuesById' => $fieldValueCollection
        ]);
        return $repositoryBundle;
    }
    
    private function contentBuilder() : ContentBuilder
    {
        $content = Mockery::mock(Content::class);
        $contentBuilder = Mockery::mock(ContentBuilder::class);
        $contentBuilder->shouldReceive(['createContent' => $content]);
        return $contentBuilder;
    }

}
