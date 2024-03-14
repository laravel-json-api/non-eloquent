<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
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
