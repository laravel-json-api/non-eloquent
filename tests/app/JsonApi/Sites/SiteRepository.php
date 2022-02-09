<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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
use App\JsonApi\Sites\Capabilities\CrudSite;
use App\JsonApi\Sites\Capabilities\CrudSiteRelations;
use App\JsonApi\Sites\Capabilities\QuerySites;
use LaravelJsonApi\Contracts\Store\CreatesResources;
use LaravelJsonApi\Contracts\Store\DeletesResources;
use LaravelJsonApi\Contracts\Store\ModifiesToMany;
use LaravelJsonApi\Contracts\Store\ModifiesToOne;
use LaravelJsonApi\Contracts\Store\QueriesAll;
use LaravelJsonApi\Contracts\Store\UpdatesResources;
use LaravelJsonApi\NonEloquent\AbstractRepository;
use LaravelJsonApi\NonEloquent\Concerns\HasCrudCapability;
use LaravelJsonApi\NonEloquent\Concerns\HasRelationsCapability;

class SiteRepository extends AbstractRepository implements
    QueriesAll,
    CreatesResources,
    UpdatesResources,
    DeletesResources,
    ModifiesToOne,
    ModifiesToMany
{

    use HasCrudCapability;
    use HasRelationsCapability;

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
    protected function crud(): CrudSite
    {
        return CrudSite::make($this->storage);
    }

    /**
     * @inheritDoc
     */
    protected function relations(): CrudSiteRelations
    {
        return CrudSiteRelations::make($this->storage);
    }

}
