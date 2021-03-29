<?php
/*
 * Copyright 2021 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace App\JsonApi\Sites;

use App\Entities\SiteStorage;
use App\JsonApi\Sites\Capabilities\CreateSite;
use App\JsonApi\Sites\Capabilities\ModifySite;
use App\JsonApi\Sites\Capabilities\ModifySiteRelationships;
use App\JsonApi\Sites\Capabilities\QuerySite;
use App\JsonApi\Sites\Capabilities\QuerySites;
use LaravelJsonApi\Contracts\Store\CreatesResources;
use LaravelJsonApi\Contracts\Store\DeletesResources;
use LaravelJsonApi\Contracts\Store\ModifiesToMany;
use LaravelJsonApi\Contracts\Store\ModifiesToOne;
use LaravelJsonApi\Contracts\Store\QueriesAll;
use LaravelJsonApi\Contracts\Store\UpdatesResources;
use LaravelJsonApi\NonEloquent\AbstractRepository;
use LaravelJsonApi\NonEloquent\Concerns\HasModifyRelationsCapability;

class SiteRepository extends AbstractRepository implements
    QueriesAll,
    CreatesResources,
    UpdatesResources,
    DeletesResources,
    ModifiesToOne,
    ModifiesToMany
{

    use HasModifyRelationsCapability;

    /**
     * @var SiteStorage
     */
    private SiteStorage $storage;

    /**
     * SiteRepository constructor.
     *
     * @param SiteStorage $storage
     */
    public function __construct(SiteStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function find(string $resourceId): ?object
    {
        return $this->storage->find($resourceId);
    }

    /**
     * @inheritDoc
     */
    public function queryAll(): QuerySites
    {
        return QuerySites::make()
            ->withServer($this->server())
            ->withSchema($this->schema());
    }

    /**
     * @inheritDoc
     */
    public function queryOne($modelOrResourceId): QuerySite
    {
        return QuerySite::make()
            ->withServer($this->server())
            ->withSchema($this->schema())
            ->withRepository($this)
            ->withModelOrResourceId($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    public function create(): CreateSite
    {
        return CreateSite::make()
            ->withServer($this->server())
            ->withSchema($this->schema());
    }

    /**
     * @inheritDoc
     */
    public function update($modelOrResourceId): ModifySite
    {
        return ModifySite::make()
            ->withServer($this->server())
            ->withSchema($this->schema())
            ->withRepository($this)
            ->withModelOrResourceId($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    public function delete($modelOrResourceId): void
    {
        $this->storage->remove($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    protected function relations(): ModifySiteRelationships
    {
        return ModifySiteRelationships::make();
    }

}
