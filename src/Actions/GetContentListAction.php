<?php

declare(strict_types=1);

namespace Marussia\Content\Actions;

use Marussia\Content\RepositoryBundle;
use Marussia\Content\Actions\Providers\GetContentListProvider;

class GetContentListAction extends AbstractAction
{
    public function __construct(RepositoryBundle $repository, GetContentListProvider $actionProvider)
    {
        $this->repository = $repository;
        $this->actionProvider = $actionProvider;
    }
    
    public function execute()
    {
        
    }
}
